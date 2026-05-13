<?php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: index.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpeedPro | Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background: #f8fafc; color: #1e293b; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .sidebar-transition { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 1024px) {
            .sidebar-off { transform: translateX(-100%); }
            .sidebar-on { transform: translateX(0); }
            .overlay-active { background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); }
        }
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="min-h-screen">

    <header class="lg:hidden bg-white border-b px-5 py-4 flex justify-between items-center sticky top-0 z-40">
        <span class="font-extrabold text-slate-900 text-lg tracking-tight uppercase">SPEEDPRO</span>
        <button onclick="toggleSidebar()" class="w-10 h-10 flex items-center justify-center bg-slate-100 rounded-xl text-slate-600">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>
    </header>

    <div class="flex">
        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 z-40 hidden lg:hidden overlay-active"></div>

        <aside id="sidebar" class="sidebar-transition sidebar-off lg:transform-none fixed lg:sticky top-0 left-0 z-50 w-72 h-screen bg-white border-r flex flex-col">
            <div class="p-8 hidden lg:flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <span class="font-extrabold text-xl text-slate-900 tracking-tighter uppercase">SPEEDPRO</span>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-2">
                <button onclick="switchTab('overview', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3.5 bg-blue-600 text-white rounded-2xl font-bold shadow-md shadow-blue-100 transition-all">
                    <i class="fa-solid fa-chart-pie w-5"></i> Overview
                </button>
                <button onclick="switchTab('projects', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:bg-slate-50 rounded-2xl transition-all font-semibold">
                    <i class="fa-solid fa-folder-open w-5"></i> My Projects
                </button>
                <button onclick="switchTab('settings', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:bg-slate-50 rounded-2xl transition-all font-semibold">
                    <i class="fa-solid fa-user-gear w-5"></i> Settings
                </button>
            </nav>

            <div class="p-6 border-t">
                <button onclick="showLogoutModal()" class="w-full flex items-center justify-center gap-3 p-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-black transition-all">
                    <i class="fa-solid fa-power-off text-red-400"></i> Logout
                </button>
            </div>
        </aside>

        <main class="flex-1 p-5 sm:p-8 lg:p-12 w-full max-w-full">
            
            <section id="overview" class="tab-content active">
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-slate-900">Hello, <?php echo explode(' ', $_SESSION['user_name'])[0]; ?>! 👋</h1>
                    <p class="text-slate-500 mt-1">Quick summary of your activity.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-4"><i class="fa-solid fa-diagram-project"></i></div>
                        <p class="text-xs font-bold text-slate-400 uppercase">Active Projects</p>
                        <p class="text-2xl font-extrabold text-slate-900">12</p>
                    </div>
                    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl mb-4"><i class="fa-solid fa-shield-check"></i></div>
                        <p class="text-xs font-bold text-slate-400 uppercase">System Status</p>
                        <p class="text-2xl font-extrabold text-green-600">Secure</p>
                    </div>
                </div>
            </section>

            <section id="projects" class="tab-content">
                <div class="mb-8">
                    <h2 class="text-3xl font-extrabold text-slate-900">My Projects</h2>
                    <p class="text-slate-500">Manage your active web development tasks.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm group hover:border-blue-300 transition-all">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center text-xl group-hover:bg-blue-600 transition-colors"><i class="fa-solid fa-code"></i></div>
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black rounded-full">ACTIVE</span>
                        </div>
                        <h4 class="font-bold text-slate-900 text-lg">M-Pesa Integration</h4>
                        <p class="text-sm text-slate-500 mt-1">Secure STK push implementation for e-commerce.</p>
                    </div>
                </div>
            </section>

            <section id="settings" class="tab-content">
                <div class="mb-8">
                    <h2 class="text-3xl font-extrabold text-slate-900">Settings</h2>
                    <p class="text-slate-500">Update your profile and security credentials.</p>
                </div>
                <div class="max-w-2xl bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold uppercase text-slate-400 ml-1">Full Name</label>
                                <input type="text" value="<?php echo $_SESSION['user_name']; ?>" disabled class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl font-medium text-slate-400 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase text-slate-400 ml-1">Role</label>
                                <input type="text" disabled value="System User" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl font-medium text-slate-400 cursor-not-allowed">
                            </div>
                        </div>
                        
                        <hr class="border-slate-100">
                        
                        <h3 class="font-bold text-lg text-slate-900">Change Password</h3>
                        <form id="passForm" onsubmit="handlePasswordUpdate(event)" class="space-y-4">
                            <input type="password" id="new_pass" placeholder="Enter new secret password" required 
                                   class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-blue-500 transition-all font-medium">
                            <button type="submit" id="passBtn" class="w-full sm:w-auto px-10 py-4 bg-blue-600 text-white rounded-2xl font-extrabold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                                <span>Update Password</span>
                            </button>
                        </form>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <div id="logoutModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[100] flex items-center justify-center p-6">
        <div class="bg-white p-8 rounded-[2.5rem] w-full max-w-sm text-center shadow-2xl">
            <h3 class="text-2xl font-black text-slate-900 mb-2">End Session?</h3>
            <p class="text-slate-500 text-sm mb-8">Are you sure you want to log out?</p>
            <div class="grid grid-cols-2 gap-3">
                <button onclick="hideLogoutModal()" class="p-4 bg-slate-100 text-slate-600 rounded-2xl font-bold">No</button>
                <a href="logout.php" class="p-4 bg-red-500 text-white rounded-2xl font-extrabold shadow-lg shadow-red-200">Yes, Exit</a>
            </div>
        </div>
    </div>

    <div id="statusModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[110] flex items-center justify-center p-6">
        <div class="bg-white p-10 rounded-[2.5rem] w-full max-w-sm text-center shadow-2xl animate-in zoom-in duration-200">
            <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">
                <i class="fa-solid fa-check"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mb-2">Updated!</h3>
            <p class="text-slate-500 text-sm mb-8">Your new password is now active.</p>
            <button onclick="hideStatusModal()" class="w-full p-4 bg-slate-900 text-white rounded-2xl font-bold">Close</button>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const s = document.getElementById('sidebar');
            const o = document.getElementById('sidebarOverlay');
            if (s.classList.contains('sidebar-off')) {
                s.classList.replace('sidebar-off', 'sidebar-on');
                o.classList.remove('hidden');
            } else {
                s.classList.replace('sidebar-on', 'sidebar-off');
                o.classList.add('hidden');
            }
        }

        function switchTab(id, btn) {
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.getElementById(id).classList.add('active');
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-100');
                b.classList.add('text-slate-500', 'hover:bg-slate-50');
            });
            btn.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-100');
            btn.classList.remove('text-slate-500', 'hover:bg-slate-50');
            if (window.innerWidth < 1024) toggleSidebar();
        }

        async function handlePasswordUpdate(e) {
            e.preventDefault();
            const btn = document.getElementById('passBtn');
            const pass = document.getElementById('new_pass').value;
            
            btn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i> Saving...';
            btn.disabled = true;

            const fd = new FormData();
            fd.append('action', 'final_reset'); // Calls existing reset logic in auth.php
            fd.append('password', pass);
            fd.append('token', '<?php echo $_SESSION['user_id']; ?>'); // Use ID as security check or current token

            try {
                await new Promise(r => setTimeout(r, 2000)); // Loading delay
                await fetch('auth.php', { method: 'POST', body: fd });
                document.getElementById('statusModal').classList.remove('hidden');
                document.getElementById('new_pass').value = '';
            } catch (err) {
                alert("Error updating password.");
            } finally {
                btn.innerHTML = 'Update Password';
                btn.disabled = false;
            }
        }

        function showLogoutModal() { document.getElementById('logoutModal').classList.remove('hidden'); }
        function hideLogoutModal() { document.getElementById('logoutModal').classList.add('hidden'); }
        function hideStatusModal() { document.getElementById('statusModal').classList.add('hidden'); }
    </script>
</body>
</html>