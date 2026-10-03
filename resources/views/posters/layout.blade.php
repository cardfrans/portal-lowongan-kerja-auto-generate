<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1080, height=1350, initial-scale=1.0">
    <title>Poster Lowongan - {{ $job->position }}</title>
    <!-- Tailwind CSS for Browsershot rendering -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @page {
            size: 1080px 1350px;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            width: 1080px;
            height: 1350px;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }
        .prose ul {
            list-style-type: disc;
            padding-left: 1.5rem;
        }
        .prose li {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body class="w-[1080px] h-[1350px] font-sans antialiased">
    @yield('content')
</body>
</html>
