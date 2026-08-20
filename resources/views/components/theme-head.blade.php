<!-- On Your Mocks - Central Design System & Color Tokens -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    brand: {
                        50: '#f5f3ff',
                        100: '#ede9fe',
                        200: '#ddd6fe',
                        300: '#c4b5fd',
                        400: '#a78bfa',
                        500: '#8b5cf6',
                        600: '#7c3aed',
                        700: '#6d28d9',
                        800: '#5b21b6',
                        900: '#4c1d95',
                        950: '#2e1065',
                    },
                    accent: {
                        blue: '#2563eb',
                        cyan: '#0ea5e9',
                        sky: '#38bdf8',
                        mint: '#0d9488',
                    },
                    dark: {
                        card: '#0b0f19',
                        surface: '#080c14',
                        footer: '#05070e',
                    }
                }
            }
        }
    }
</script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

    body {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    [x-cloak] { display: none !important; }

    .gradient-text-purple-blue {
        background: linear-gradient(135deg, #7c3aed 0%, #0ea5e9 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .gradient-text-blue {
        background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .gradient-text-purple {
        background: linear-gradient(135deg, #9333ea 0%, #6366f1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .gradient-btn-primary {
        background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
    }
</style>
