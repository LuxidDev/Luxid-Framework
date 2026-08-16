<?php

component('layouts/AppLayout', function ($c) {
  $c->state(function () {
    return [
      'title' => 'Luxid Framework',
      'description' => 'Modern PHP framework with reactive Nova components'
    ];
  });

  $c->view(function ($state) {
    $content = \Luxid\Nova\Slot::render('content', '');
?>
    <!DOCTYPE html>
    <html lang="en" class="scroll-smooth">

    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- Nova reads this token when posting component actions; without it the
           server rejects the call. -->
      @raw(\Luxid\Nova\Csrf::metaTag())
      <title>@echo($state->title)</title>
      <link rel="icon" href="/favicon.ico" type="image/x-icon">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

      <script src="https://cdn.tailwindcss.com"></script>

      <style>
        @keyframes gradientFlow {

          0%,
          100% {
            background-position: 0% 50%;
          }

          50% {
            background-position: 100% 50%;
          }
        }

        @keyframes pulse-glow {

          0%,
          100% {
            opacity: 0.05;
            filter: blur(40px);
          }

          50% {
            opacity: 0.1;
            filter: blur(60px);
          }
        }

        @keyframes fadeInUp {
          from {
            opacity: 0;
            transform: translateY(30px);
          }

          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        .gradient-bg {
          background: linear-gradient(-45deg, #000000, #111111, #222222, #000000);
          background-size: 400% 400%;
          animation: gradientFlow 20s ease infinite;
        }

        .gradient-text {
          background: linear-gradient(135deg, #ffffff 0%, #cccccc 25%, #999999 50%, #cccccc 75%, #ffffff 100%);
          background-size: 300% 300%;
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          background-clip: text;
          animation: gradientFlow 8s ease infinite;
        }

        .glass-effect {
          background: rgba(0, 0, 0, 0.4);
          backdrop-filter: blur(10px);
          border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glow-orb {
          position: absolute;
          border-radius: 50%;
          filter: blur(60px);
          animation: pulse-glow 8s ease-in-out infinite;
          pointer-events: none;
        }

        .animate-fade-in-up {
          animation: fadeInUp 0.8s ease-out forwards;
        }

        .delay-100 {
          animation-delay: 0.1s;
        }

        .delay-200 {
          animation-delay: 0.2s;
        }

        .delay-300 {
          animation-delay: 0.3s;
        }

        [class*="delay-"] {
          opacity: 0;
        }

        .bg-grid {
          background-image:
            linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
          background-size: 50px 50px;
        }

        .pixel-grid {
          background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
          background-size: 20px 20px;
          background-position: -10px -10px;
        }

        .hover-lift {
          transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
          transform: translateY(-5px);
          box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }
      </style>
    </head>

    <body class="bg-black text-white overflow-x-hidden">
      <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 bg-grid opacity-20"></div>
        <div class="glow-orb w-96 h-96 bg-white top-0 -left-48 opacity-5"></div>
        <div class="glow-orb w-80 h-80 bg-gray-800 top-1/3 right-0 opacity-10"></div>
        <div class="glow-orb w-72 h-72 bg-gray-900 bottom-0 left-1/4 opacity-10"></div>
      </div>

      <?php
      echo \Luxid\Nova\Slot::render('content', '<div class="min-h-screen flex items-center justify-center"><div class="text-center"><h1 class="text-4xl">Default Content</h1></div></div>');
      ?>

      <script src="/nova.js"></script>
    </body>

    </html>
<?php
  });
});
