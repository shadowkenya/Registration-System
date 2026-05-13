<?php include('db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password | Speed Projects</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); 
            color: #1e293b; 
            min-height: 100vh; 
            font-family: 'Inter', sans-serif;
        }
        .glass-white { 
            background: rgba(255, 255, 255, 0.8); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(255, 255, 255, 1); 
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }
        .modal-bg { background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); }
        
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
<body class="flex items-center justify-center p-4">

    <div class="glass-white p-8 w-full max-w-[400px] rounded-2xl">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-extrabold text-slate-900">Secure Reset</h2>
            <p class="text-slate-500 mt-2 text-sm">Please enter your new secure password</p>
        </div>

        <?php 
        $token = $_GET['token'] ?? '';
        if ($token): 
        ?>
        <form id="resetForm" onsubmit="handleReset(event)" class="space-y-4">
            <input type="hidden" name="action" value="final_reset">
            <input type="hidden" name="token" id="token" value="<?php echo htmlspecialchars($token); ?>">
            
            <div class="space-y-1">
                <label class="text-xs font-bold uppercase text-slate-500 ml-1">New Password</label>
                <input type="password" name="password" id="new_password" required placeholder="••••••••" 
                       class="w-full p-3.5 bg-white border border-slate-200 rounded-xl outline-none focus:border-blue-500 transition-all">
            </div>

            <button type="submit" id="resetBtn" class="w-full bg-slate-900 hover:bg-black text-white p-3.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                <span>Update Password</span>
            </button>
        </form>
        <?php else: ?>
            <div class="text-center">
                <p class="text-red-500 font-medium">No valid token found.</p>
                <a href="index.php" class="text-blue-600 text-sm hover:underline mt-2 block">Return to Login</a>
            </div>
        <?php endif; ?>
    </div>

    <div id="successModal" class="hidden fixed inset-0 modal-bg flex items-center justify-center p-4 z-50">
        <div class="bg-white p-10 text-center max-w-[350px] rounded-2xl shadow-2xl">
            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl font-bold">✔</div>
            <h3 class="text-2xl font-bold mb-2 text-slate-900">Updated!</h3>
            <p class="text-slate-500">Your password has been changed. Redirecting to login...</p>
        </div>
    </div>

    <script>
        async function handleReset(event) {
            event.preventDefault();
            const btn = document.getElementById('resetBtn');
            const originalText = btn.innerHTML;

            // 1. Loading State
            btn.innerHTML = `<div class="spinner"></div> <span>Saving...</span>`;
            btn.disabled = true;

            // 2. Wait 3 Seconds
            await new Promise(resolve => setTimeout(resolve, 3000));

            // 3. Submit to auth.php
            const formData = new FormData();
            formData.append('action', 'final_reset');
            formData.append('token', document.getElementById('token').value);
            formData.append('password', document.getElementById('new_password').value);

            fetch('auth.php', { method: 'POST', body: formData })
            .then(response => response.text())
            .then(data => {
                // Show success modal
                document.getElementById('successModal').classList.remove('hidden');
                
                // 4. Redirect to login
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 2000);
            })
            .catch(error => {
                alert('Error updating password.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>