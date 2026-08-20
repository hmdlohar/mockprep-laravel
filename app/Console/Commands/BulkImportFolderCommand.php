<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Import\Actions\CommitImportAction;
use App\Services\Import\DuplicateDetector;
use App\Services\Import\ImportParserManager;
use Illuminate\Console\Command;

class BulkImportFolderCommand extends Command
{
    protected $signature = 'import:folder {path : The absolute or relative path to the folder of test JSON/JSONP files} {--dry-run : Only analyze and count without saving}';
    protected $description = 'Batch scan and import all question files from a directory with duplicate detection and passage deduplication';

    public function handle(): int
    {
        $folderPath = $this->argument('path');
        $isDryRun = (bool) $this->option('dry-run');

        if (!is_dir($folderPath)) {
            $this->error("Directory not found: {$folderPath}");
            return self::FAILURE;
        }

        $files = glob(rtrim($folderPath, '/') . '/*');
        $this->info("Found " . count($files) . " files in [{$folderPath}]. Starting batch processor...");

        $manager = new ImportParserManager();
        $detector = new DuplicateDetector();
        $action = new CommitImportAction();

        $totalQuestionsScanned = 0;
        $totalNewQuestions = 0;
        $totalDuplicates = 0;
        $totalImportedQuestions = 0;
        $totalImportedPassages = 0;
        $totalImportedTopics = 0;
        $skippedFiles = 0;

        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $filePath) {
            $fileName = basename($filePath);

            try {
                $raw = file_get_contents($filePath);
                $parser = $manager->getParserForFile($fileName);
                $batch = $parser->parse($raw, $fileName);

                if ($batch->totalQuestions === 0) {
                    $skippedFiles++;
                    $bar->advance();
                    continue;
                }

                $batch = $detector->detect($batch);

                $totalQuestionsScanned += $batch->totalQuestions;
                $totalNewQuestions += $batch->newQuestionsCount;
                $totalDuplicates += $batch->duplicateQuestionsCount;

                if (!$isDryRun && $batch->newQuestionsCount > 0) {
                    $res = $action->execute($batch);
                    $totalImportedQuestions += $res['imported_questions'];
                    $totalImportedPassages += $res['imported_passages'];
                    $totalImportedTopics += $res['imported_topics'];
                }
            } catch (\Throwable $e) {
                $skippedFiles++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("=== Import Processing Summary ===");
        $this->line("Mode: " . ($isDryRun ? '<comment>DRY-RUN (No changes made)</comment>' : '<info>COMMITTED</info>'));
        $this->line("Total Files Scanned: " . count($files));
        $this->line("Skipped/Invalid Files: {$skippedFiles}");
        $this->line("Total Questions Scanned: {$totalQuestionsScanned}");
        $this->line("Duplicates Identified & Skipped: {$totalDuplicates}");
        $this->line("New Questions Found: {$totalNewQuestions}");

        if (!$isDryRun) {
            $this->info("✓ Total Questions Added to Bank: {$totalImportedQuestions}");
            $this->info("✓ New Passages Deduplicated & Saved: {$totalImportedPassages}");
            $this->info("✓ Topics Categorized: {$totalImportedTopics}");
        }

        return self::SUCCESS;
    }
}
