<?php include('db.php'); session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Speed Projects | Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); 
            color: #1e293b; 
            min-height: 100vh; 
            font-family: 'Inter', system-ui, sans-serif;
        }
        .glass-white { 
            background: rgba(255, 255, 255, 0.8); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(255, 255, 255, 1); 
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }
        .modal-bg { background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); }
        
        /* Loading Spinner Animation */
        .spinner {
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top: 3px solid #fff;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body class="flex items-center justify-center p-4 sm:p-6">

    <div class="glass-white p-6 sm:p-10 w-full max-w-[420px] rounded-2xl">
        <div class="text-center mb-8">
            <h2 id="formTitle" class="text-3xl font-extrabold text-slate-900">Welcome Back</h2>
            <p id="formSub" class="text-slate-500 mt-2 text-sm">Please enter your details to continue</p>
        </div>
        
        <form id="loginForm" onsubmit="handleAuth(event, 'login')" class="space-y-5">
            <input type="hidden" name="action" value="login">
            <div class="space-y-1">
                <label class="text-xs font-bold uppercase text-slate-500 ml-1">Email Address</label>
                <input type="email" name="identity" id="login_email" required placeholder="name@example.com" 
                       class="w-full p-3.5 bg-white border border-slate-200 rounded-xl outline-none focus:border-blue-500 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold uppercase text-slate-500 ml-1">Password</label>
                <input type="password" name="password" id="login_pass" required placeholder="••••••••" 
                       class="w-full p-3.5 bg-white border border-slate-200 rounded-xl outline-none focus:border-blue-500 transition-all">
            </div>
            <button type="submit" id="loginBtn" class="w-full bg-slate-900 hover:bg-black text-white p-3.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                <span>Sign In</span>
            </button>
            <div class="flex justify-between text-sm pt-2">
                <button type="button" onclick="toggleForm('register')" class="text-slate-600 font-medium">Create Account</button>
                <button type="button" onclick="showModal('forgotModal')" class="text-blue-600 font-semibold">Forgot Password?</button>
            </div>
        </form>

        <form id="regForm" action="auth.php" method="POST" class="hidden space-y-4">
            <input type="hidden" name="action" value="register">
            <input type="text" name="full_name" placeholder="Full Name" required class="w-full p-3.5 bg-white border border-slate-200 rounded-xl outline-none">
            <input type="email" name="email" placeholder="Email Address" required class="w-full p-3.5 bg-white border border-slate-200 rounded-xl outline-none">
            <input type="password" name="password" placeholder="Create Password" required class="w-full p-3.5 bg-white border border-slate-200 rounded-xl outline-none">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required class="w-full p-3.5 bg-white border border-slate-200 rounded-xl outline-none">
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white p-3.5 rounded-xl font-bold shadow-lg shadow-green-100">Register Now</button>
            <button type="button" onclick="toggleForm('login')" class="w-full text-center text-sm text-slate-500 font-medium mt-2">Back to Login</button>
        </form>
    </div>

    <div id="forgotModal" class="hidden fixed inset-0 modal-bg flex items-center justify-center p-4 z-50">
        <div class="bg-white p-8 w-full max-w-[380px] rounded-2xl shadow-2xl">
            <h3 class="text-xl font-bold mb-2">Reset Password</h3>
            <p class="text-sm text-slate-500 mb-6">Enter email for recovery link.</p>
            <form onsubmit="handleAuth(event, 'forgot')" class="space-y-4">
                <input type="email" id="forgot_email" required placeholder="your@email.com" class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl outline-none">
                <button type="submit" id="forgotBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                    <span>Send Reset Link</span>
                </button>
                <button type="button" onclick="hideModal('forgotModal')" class="w-full text-sm text-slate-400">Cancel</button>
            </form>
        </div>
    </div>

    <div id="successModal" class="hidden fixed inset-0 modal-bg flex items-center justify-center p-4 z-50">
        <div class="bg-white p-10 text-center max-w-[350px] rounded-2xl shadow-2xl">
            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl font-bold">✔</div>
            <h3 id="successTitle" class="text-2xl font-bold mb-2">Success!</h3>
            <p id="successText" class="text-slate-500">Action completed successfully.</p>
        </div>
    </div>

    <script>
        function toggleForm(type) {
            const isReg = type === 'register';
            document.getElementById('regForm').classList.toggle('hidden', !isReg);
            document.getElementById('loginForm').classList.toggle('hidden', isReg);
            document.getElementById('formTitle').innerText = isReg ? 'Create Account' : 'Welcome Back';
        }

        function showModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function hideModal(id) { document.getElementById(id).classList.add('hidden'); }

        async function handleAuth(event, type) {
            event.preventDefault();
            const btn = type === 'login' ? document.getElementById('loginBtn') : document.getElementById('forgotBtn');
            const originalContent = btn.innerHTML;
            
            // 1. Show Loading State
            btn.innerHTML = `<div class="spinner"></div> <span>Processing...</span>`;
            btn.disabled = true;

            // 2. Wait 3 Seconds
            await new Promise(resolve => setTimeout(resolve, 3000));

            // 3. Submit Data to auth.php via Fetch
            const formData = new FormData();
            formData.append('action', type);
            if(type === 'login') {
                formData.append('identity', document.getElementById('login_email').value);
                formData.append('password', document.getElementById('login_pass').value);
            } else {
                formData.append('email', document.getElementById('forgot_email').value);
            }

            fetch('auth.php', { method: 'POST', body: formData })
            .then(response => response.text())
            .then(data => {
                if (data.includes('dashboard.php') || data.includes('admin/index.php')) {
                    // Show Success Modal for Login
                    document.getElementById('successTitle').innerText = "Access Granted";
                    document.getElementById('successText').innerText = "Logging into your dashboard...";
                    showModal('successModal');
                    setTimeout(() => { 
                        if(data.includes('admin/index.php')) window.location.href = 'admin/index.php';
                        else window.location.href = 'dashboard.php';
                    }, 1500);
                } else if (type === 'forgot' && data.includes('Reset link sent')) {
                    // Show Success Modal for Forgot Password
                    hideModal('forgotModal');
                    document.getElementById('successTitle').innerText = "Email Sent";
                    document.getElementById('successText').innerText = "Check your inbox for the reset link.";
                    showModal('successModal');
                    setTimeout(() => { window.location.href = 'index.php'; }, 3000);
                } else {
                    // Handle Errors (Show alert or reset button)
                    alert('Action failed. Please check your credentials.');
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }
            });
        }

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('registered')) {
            showModal('successModal');
            document.getElementById('successTitle').innerText = "Success!";
            document.getElementById('successText').innerText = "Account created. Redirecting...";
            setTimeout(() => { window.location.href = 'index.php'; }, 3000);
        }
    </script>
</body>
</html>