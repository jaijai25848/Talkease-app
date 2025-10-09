<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>TalkEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-white via-blue-50 to-purple-50 min-h-screen font-sans text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <div class="bg-purple-600 text-white px-3 py-1 rounded-full font-bold text-xl">T</div>
                    <span class="text-2xl font-semibold text-gray-800">Talk<span class="text-purple-600">Ease</span></span>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex space-x-8 font-medium items-center">
                    <a href="#" class="hover:text-purple-600">Home</a>
                    <a href="#features" class="hover:text-purple-600">Features</a>
                    <a href="#how-it-works" class="hover:text-purple-600">How It Works</a>
                     <a href="#objectives" class="hover:text-purple-600">Objectives</a>
                     <a href="#team" class="hover:text-purple-600">Team</a>
                   
                    <a href="{{ route('login') }}"
                       class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded-lg font-semibold transition">
                      Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="grid md:grid-cols-2 gap-6 items-center px-6 md:px-10 py-20 max-w-7xl mx-auto">
        <!-- Text Content -->
        <div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight">
                AI-Powered Pronunciation <br /> & Articulation Coach
            </h1>
            <p class="mt-4 text-lg text-gray-600">
                Enhance your pronunciation and articulation skills with our innovative mobile app powered by Artificial Intelligence and expert coaching modules. Perfect for the English context community.
            </p>

            <div class="flex flex-wrap mt-6 gap-4">
                <a href="#" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold shadow">Download App</a>
                <a href="#" class="border border-purple-600 text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-purple-100">Learn More</a>
            </div>

            <div class="flex flex-wrap gap-4 mt-6 text-sm text-gray-500 font-medium">
                <span class="flex items-center gap-1"><span class="w-2 h-2 bg-green-500 rounded-full"></span> AI-Powered</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 bg-green-500 rounded-full"></span> Expert Coaching</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 bg-green-500 rounded-full"></span> Real-time Feedback</span>
            </div>
        </div>

        <!-- Hero Image -->
        <div class="rounded-lg overflow-hidden shadow-xl">
            <img src="{{ asset('asset/images/hero.jpg') }}" alt="Microphone" class="w-full rounded-lg shadow-lg">
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="px-6 py-20 max-w-7xl mx-auto scroll-mt-20">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">
            Powerful Features for <span class="text-blue-600">Enhanced Learning</span>
        </h2>
        <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
            Discover how TalkEase combines cutting-edge AI technology with expert guidance to transform your pronunciation and articulation skills.
        </p>

        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition">
                <img src="{{ asset('asset/images/ai.jpg') }}" alt="AI Speech" class="rounded-lg mb-4 w-full h-40 object-cover">
                <h3 class="font-bold text-lg text-gray-800 mb-2">AI Speech Recognition</h3>
                <p class="text-sm text-gray-600">Machine learning models analyze your speech and provide feedback on pronunciation and articulation.</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition">
                <img src="{{ asset('asset/images/human.jpeg') }}" alt="Coaching" class="rounded-lg mb-4 w-full h-40 object-cover">
                <h3 class="font-bold text-lg text-gray-800 mb-2">Expert Coaching Sessions</h3>
                <p class="text-sm text-gray-600">Access sessions with experts who guide and support your articulation development.</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition">
                <img src="{{ asset('asset/images/mobile.jpg') }}" alt="Mobile UI" class="rounded-lg mb-4 w-full h-40 object-cover">
                <h3 class="font-bold text-lg text-gray-800 mb-2">Interactive Mobile Interface</h3>
                <p class="text-sm text-gray-600">User-friendly interface designed for engaging and intuitive mobile experiences.</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition">
                <img src="{{ asset('asset/images/Progress tracking.jpeg') }}" alt="Progress Tracking" class="rounded-lg mb-4 w-full h-40 object-cover">
                <h3 class="font-bold text-lg text-gray-800 mb-2">Progress Dashboard</h3>
                <p class="text-sm text-gray-600">Track clarity, speed, and accuracy over time with detailed progress analytics.</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition">
                <img src="{{ asset('asset/images/instant.jpg') }}" alt="Instant Feedback" class="rounded-lg mb-4 w-full h-40 object-cover">
                <h3 class="font-bold text-lg text-gray-800 mb-2">Instant Feedback System</h3>
                <p class="text-sm text-gray-600">Receive real-time corrections and suggestions as you practice pronunciation.</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition">
                <img src="{{ asset('asset/images/person.jpg') }}" alt="Personalized Learning" class="rounded-lg mb-4 w-full h-40 object-cover">
                <h3 class="font-bold text-lg text-gray-800 mb-2">Personalized Learning</h3>
                <p class="text-sm text-gray-600">Tailored exercises based on your assessment and learning goals for optimal results.</p>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="px-6 py-20 max-w-7xl mx-auto scroll-mt-20">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">
            How <span class="text-blue-600">TalkEase</span> Works
        </h2>
        <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
            Our sophisticated system architecture ensures seamless user experience while delivering powerful pronunciation coaching capabilities.
        </p>

        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div class="rounded-lg overflow-hidden shadow-md">
                <img src="{{ asset('asset/images/chatgpt.jpeg') }}" alt="Architecture" class="w-full">
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Secure & Intelligent Architecture</h3>
                <p class="text-gray-600 mb-4">
                    The system starts with secure OAuth 2.0 authentication, ensuring your data privacy. After successful login, you interact with our intuitive mobile app that seamlessly communicates with our advanced AI engine.
                </p>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white w-6 h-6 flex items-center justify-center rounded-full text-sm font-bold mr-3">1</span>
                        Secure OAuth 2.0 Authentication
                    </li>
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white w-6 h-6 flex items-center justify-center rounded-full text-sm font-bold mr-3">2</span>
                        AI Speech Analysis Engine
                    </li>
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white w-6 h-6 flex items-center justify-center rounded-full text-sm font-bold mr-3">3</span>
                        Real-time Feedback Delivery
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-16 bg-white shadow-md rounded-xl p-6 md:p-10">
            <h3 class="text-xl font-semibold text-center text-gray-800 mb-8">Technology Stack</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6 text-center">
                <div>
                    <div class="bg-blue-100 text-blue-600 font-bold text-lg w-12 h-12 mx-auto flex items-center justify-center rounded-lg">RN</div>
                    <p class="mt-2 font-medium text-sm">React Native</p>
                    <p class="text-xs text-gray-500">Cross-platform mobile development</p>
                </div>
                <div>
                    <div class="bg-green-100 text-green-600 font-bold text-lg w-12 h-12 mx-auto flex items-center justify-center rounded-lg">API</div>
                    <p class="mt-2 font-medium text-sm">RESTful API</p>
                    <p class="text-xs text-gray-500">Backend communication</p>
                </div>
                <div>
                    <div class="bg-yellow-100 text-yellow-600 font-bold text-lg w-12 h-12 mx-auto flex items-center justify-center rounded-lg">🔒</div>
                    <p class="mt-2 font-medium text-sm">OAuth 2.0</p>
                    <p class="text-xs text-gray-500">Secure authentication</p>
                </div>
                <div>
                    <div class="bg-orange-100 text-orange-600 font-bold text-lg w-12 h-12 mx-auto flex items-center justify-center rounded-lg">ML</div>
                    <p class="mt-2 font-medium text-sm">Machine Learning</p>
                    <p class="text-xs text-gray-500">Speech analysis models</p>
                </div>
                <div>
                    <div class="bg-indigo-100 text-indigo-600 font-bold text-lg w-12 h-12 mx-auto flex items-center justify-center rounded-lg">☁️</div>
                    <p class="mt-2 font-medium text-sm">Cloud Storage</p>
                    <p class="text-xs text-gray-500">Scalable data storage</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Objectives Section -->
<section id="objectives" class="px-6 py-20 max-w-7xl mx-auto scroll-mt-20">
    <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">
        Project <span class="text-blue-600">Objectives</span>
    </h2>
    <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
        Our comprehensive roadmap ensures successful development, deployment, and continuous improvement of the TalkEase application.
    </p>

    <div class="space-y-6">
        <!-- Objective 1 -->
        <div class="bg-white rounded-xl shadow-md p-6 flex items-start space-x-4">
            <div class="bg-blue-600 text-white font-bold text-sm px-3 py-2 rounded-lg">01</div>
            <div>
                <h3 class="font-bold text-lg text-gray-800">Requirements & Architecture Design</h3>
                <p class="text-sm text-gray-600">Identify system requirements and design the architecture based on surveys, focus groups, and benchmarking to ensure optimal user experience.</p>
            </div>
        </div>

        <!-- Objective 2 -->
        <div class="bg-white rounded-xl shadow-md p-6 flex items-start space-x-4">
            <div class="bg-purple-600 text-white font-bold text-sm px-3 py-2 rounded-lg">02</div>
            <div>
                <h3 class="font-bold text-lg text-gray-800">Core Feature Development</h3>
                <p class="text-sm text-gray-600">Develop key features including AI speech recognition, NLP feedback, expert coaching tools, and secure OAuth 2.0 login functionality.</p>
            </div>
        </div>

        <!-- Objective 3 -->
        <div class="bg-white rounded-xl shadow-md p-6 flex items-start space-x-4">
            <div class="bg-green-600 text-white font-bold text-sm px-3 py-2 rounded-lg">03</div>
            <div>
                <h3 class="font-bold text-lg text-gray-800">Comprehensive Testing</h3>
                <p class="text-sm text-gray-600">Conduct thorough testing through beta tests, performance benchmarking, and security audits to ensure reliability and security.</p>
            </div>
        </div>

        <!-- Objective 4 -->
        <div class="bg-white rounded-xl shadow-md p-6 flex items-start space-x-4">
            <div class="bg-orange-500 text-white font-bold text-sm px-3 py-2 rounded-lg">04</div>
            <div>
                <h3 class="font-bold text-lg text-gray-800">Launch & Support</h3>
                <p class="text-sm text-gray-600">Launch the app on iOS and Android, provide comprehensive training materials, and establish robust user support systems.</p>
            </div>
        </div>

        <!-- Objective 5 -->
        <div class="bg-white rounded-xl shadow-md p-6 flex items-start space-x-4">
            <div class="bg-indigo-500 text-white font-bold text-sm px-3 py-2 rounded-lg">05</div>
            <div>
                <h3 class="font-bold text-lg text-gray-800">Monitoring & Enhancement</h3>
                <p class="text-sm text-gray-600">Track user engagement, measure pronunciation improvement, gather feedback, and plan for future enhancements and feature updates.</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section id="team" class="bg-blue-50 px-6 py-20 max-w-7xl mx-auto scroll-mt-20 rounded-lg">
    <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">
        Meet Our <span class="text-blue-600">Expert Team</span>
    </h2>
    <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
        A talented group of developers, designers, and researchers dedicated to revolutionizing pronunciation learning through innovative technology.
    </p>

    <!-- Project Adviser -->
    <div class="flex justify-center mb-12">
        <div class="bg-white shadow-lg rounded-xl p-6 w-full max-w-md text-center">
            <img src="{{ asset('asset/images/rexal.jpg') }}"
                 alt="Rexal Toledo"
                 class="w-24 h-24 mx-auto rounded-full object-cover mb-4 border-4 border-blue-200">
            <h3 class="font-bold text-lg text-gray-800">REXAL TOLEDO</h3>
            <p class="text-sm font-semibold text-blue-600 mb-1">Project Adviser</p>
            <p class="text-sm text-gray-600">
                Leading academic expert providing strategic guidance and mentorship throughout the project development lifecycle.
            </p>
        </div>
    </div>

    <!-- Team Members Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @php
            $members = [
                ['initials' => 'RML', 'name' => 'Renzo Marlou Llanto', 'role' => 'Lead Developer',   'image' => 'llanto.jpg'],
                ['initials' => 'JFI', 'name' => 'Jireh Faith Ilogon','role' => 'AI Specialist',     'image' => 'ilogon.jpg'],
                ['initials' => 'JMV', 'name' => 'John Mark Vecina',   'role' => 'Mobile Developer',  'image' => 'vecina.jpg'],
                ['initials' => 'JR',  'name' => 'Jamaica Reloz',      'role' => 'Backend Developer','image' => 'reloz.jpg'],
                ['initials' => 'SR',  'name' => 'Shiena Reloz',       'role' => 'Quality Assurance', 'image' => 'shiena.jpg']
            ];
        @endphp

        @foreach ($members as $member)
            <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
                @if (!empty($member['image']))
                    <img src="{{ asset('asset/images/'.$member['image']) }}"
                         alt="{{ $member['name'] }}"
                         class="w-24 h-24 mx-auto rounded-full object-cover mb-4 border-4 border-blue-200">
                @else
                    <div class="bg-gradient-to-r from-purple-400 to-blue-400
                                text-white w-24 h-24 mx-auto flex items-center justify-center
                                rounded-full font-bold text-xl mb-4">
                        {{ $member['initials'] }}
                    </div>
                @endif

                <h3 class="font-semibold text-gray-800">{{ $member['name'] }}</h3>
                <p class="text-sm font-medium text-purple-600 mb-1">{{ $member['role'] }}</p>
                <p class="text-sm text-gray-600">
                    Dedicated team member contributing expertise in {{ strtolower($member['role']) }}
                    to deliver exceptional user experience.
                </p>
            </div>
        @endforeach
    </div>
</section>

<!-- Target Community Section -->
<section id="community" class="px-6 py-20 max-w-7xl mx-auto scroll-mt-20">
  <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">
    Target <span class="text-blue-600">Community</span>
  </h2>
  <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
    Initially designed for the Bontoc Campus community, TalkEase addresses the specific needs of Filipino-English bilingual speakers.
  </p>

  <div class="grid md:grid-cols-2 gap-8 items-center">
    <!-- Image -->
    <div class="rounded-lg overflow-hidden shadow-md">
      <img src="{{ asset('asset/images/bontoc.jpg') }}" alt="Bontoc Community" class="w-full object-cover">
    </div>

    <!-- Info -->
    <div>
      <h3 class="text-xl font-bold text-gray-800 mb-4">Bontoc Campus Community</h3>
      <p class="text-gray-600 mb-4">
        Our initial focus is on serving the Bontoc Campus community with a bilingual Filipino-English mobile application designed specifically for their linguistic needs.
      </p>
      <div class="space-y-3">
        <div class="bg-blue-100 px-4 py-2 rounded-md text-sm text-blue-800 font-medium">📱 Platform Availability — Available for both iOS and Android devices</div>
        <div class="bg-purple-100 px-4 py-2 rounded-md text-sm text-purple-800 font-medium">🌐 Language Support — Tailored for Filipino-English pronunciation</div>
        <div class="bg-green-100 px-4 py-2 rounded-md text-sm text-green-800 font-medium">🎯 Targeted Learning — Customized pronunciation exercises</div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12 text-center rounded-xl mx-6 md:mx-auto max-w-5xl">
  <h2 class="text-2xl md:text-3xl font-bold mb-4">Ready to Improve Your Pronunciation?</h2>
  <p class="mb-6">Join the Bontoc Campus community in revolutionizing language learning with AI-powered coaching.</p>
  <div class="flex flex-wrap justify-center gap-4">
    <a href="#" class="bg-white text-blue-700 px-6 py-3 rounded-lg font-semibold shadow hover:bg-blue-100">Download for iOS</a>
    <a href="#" class="bg-purple-800 text-white px-6 py-3 rounded-lg font-semibold shadow hover:bg-purple-900">Download for Android</a>
  </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-gray-200 mt-20 px-6 py-10">
  <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-8">
    <!-- Logo and About -->
    <div>
      <div class="flex items-center mb-3">
        <div class="bg-purple-600 text-white px-2 py-1 rounded-full font-bold text-xl">T</div>
        <span class="ml-2 font-bold text-lg text-white">TalkEase</span>
      </div>
      <p class="text-sm text-gray-400 mb-4">Empowering the Bontoc Campus community with AI-driven pronunciation and articulation coaching for enhanced Filipino-English communication skills.</p>
      <div class="flex space-x-3">
        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-twitter"></i></a>
        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
      </div>
    </div>

    <!-- Features -->
    <div>
      <h4 class="font-semibold text-white mb-3">Features</h4>
      <ul class="text-sm text-gray-400 space-y-1">
        <li><a href="#features" class="hover:text-white">AI Speech Recognition</a></li>
        <li><a href="#features" class="hover:text-white">Expert Coaching</a></li>
        <li><a href="#features" class="hover:text-white">Progress Tracking</a></li>
        <li><a href="#features" class="hover:text-white">Real-time Feedback</a></li>
      </ul>
    </div>

    <!-- Support -->
    <div>
      <h4 class="font-semibold text-white mb-3">Support</h4>
      <ul class="text-sm text-gray-400 space-y-1">
        <li><a href="#" class="hover:text-white">Help Center</a></li>
        <li><a href="#" class="hover:text-white">Contact Team</a></li>
        <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
        <li><a href="#" class="hover:text-white">Terms of Service</a></li>
      </ul>
    </div>
  </div>
  <div class="text-center text-sm text-gray-500 mt-8 border-t border-gray-700 pt-4">
    &copy; 2025 TalkEase. Developed by the Bontoc Campus Team under the guidance of Rexal Toledo. All rights reserved.
  </div>
</footer>



</body>
</html>
