<?php
session_start();
include('../db.php');

// Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$current_file = basename(__FILE__);

// Handle Deletion Logic
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'user'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: $current_file?msg=deleted");
    exit();
}

// Fetch Data
$users = $conn->query("SELECT * FROM users WHERE role='user'");
$total_users = $users->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Speed Admin | Elite Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .view-section { display: none; animation: fadeIn 0.4s ease-out; }
        .view-section.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .glass-card { background: white; border: 1px solid #f1f5f9; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05); }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-900 font-sans">

    <div class="md:hidden flex items-center justify-between bg-white px-6 py-4 border-b sticky top-0 z-50">
        <div class="flex items-center gap-2 text-blue-600">
            <i class="fas fa-bolt font-black"></i>
            <span class="font-black tracking-tighter">SPEED</span>
        </div>
        <button id="mobile-toggle" class="p-2 text-slate-600 bg-slate-50 rounded-lg">
            <i class="fas fa-bars-staggered"></i>
        </button>
    </div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 bg-white border-r w-64 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-50 flex flex-col">
        <div class="p-8">
            <div class="flex items-center gap-3 mb-10">
                <div class="bg-blue-600 h-10 w-10 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                    <i class="fas fa-bolt text-lg"></i>
                </div>
                <span class="text-2xl font-black tracking-tighter">SPEED<span class="text-blue-600">.</span></span>
            </div>

            <nav class="space-y-2">
                <button onclick="showSection('home', this)" class="nav-btn w-full flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-xl font-bold transition shadow-lg shadow-blue-100">
                    <i class="fas fa-grid-2 w-5"></i> Dashboard
                </button>
                <button onclick="showSection('users', this)" class="nav-btn w-full flex items-center gap-3 text-slate-400 hover:bg-slate-50 px-4 py-3 rounded-xl font-bold transition">
                    <i class="fas fa-users w-5"></i> Clients
                </button>
                <button onclick="showSection('analytics', this)" class="nav-btn w-full flex items-center gap-3 text-slate-400 hover:bg-slate-50 px-4 py-3 rounded-xl font-bold transition">
                    <i class="fas fa-chart-line w-5"></i> Analytics
                </button>
            </nav>
        </div>
        
        <div class="mt-auto p-8 border-t border-slate-50">
            <a href="../logout.php" class="flex items-center gap-3 text-red-500 font-bold px-4 py-3 hover:bg-red-50 rounded-xl transition">
                <i class="fas fa-arrow-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <div id="overlay" class="fixed inset-0 bg-slate-900/40 hidden z-40 backdrop-blur-sm"></div>

    <main class="md:ml-64 p-5 md:p-12">

        <section id="home" class="view-section active">
            <header class="mb-10">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">System Overview</h1>
                <p class="text-slate-500 font-medium">Monitoring your platform performance.</p>
            </header>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
                <div class="glass-card p-6 rounded-[2rem] relative overflow-hidden group">
                    <div class="absolute right-0 top-0 bg-blue-50 h-24 w-24 rounded-bl-[4rem] -mr-8 -mt-8 transition-all group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="h-12 w-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-blue-200">
                            <i class="fas fa-user-group"></i>
                        </div>
                        <p class="text-slate-400 text-xs font-black uppercase tracking-widest">Registered Clients</p>
                        <h2 class="text-4xl font-black mt-1"><?php echo $total_users; ?></h2>
                    </div>
                </div>

                <div class="glass-card p-6 rounded-[2rem] relative overflow-hidden group">
                    <div class="absolute right-0 top-0 bg-emerald-50 h-24 w-24 rounded-bl-[4rem] -mr-8 -mt-8 transition-all group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="h-12 w-12 bg-emerald-500 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-emerald-200">
                            <i class="fas fa-shield-check"></i>
                        </div>
                        <p class="text-slate-400 text-xs font-black uppercase tracking-widest">Server Status</p>
                        <h2 class="text-4xl font-black mt-1 text-emerald-600">Online</h2>
                    </div>
                </div>

                <div class="glass-card p-6 rounded-[2rem] relative overflow-hidden group">
                    <div class="absolute right-0 top-0 bg-orange-50 h-24 w-24 rounded-bl-[4rem] -mr-8 -mt-8 transition-all group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="h-12 w-12 bg-orange-500 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-orange-200">
                            <i class="fas fa-server"></i>
                        </div>
                        <p class="text-slate-400 text-xs font-black uppercase tracking-widest">Storage Sync</p>
                        <h2 class="text-4xl font-black mt-1">99.9%</h2>
                    </div>
                </div>
            </div>
            
            <div class="glass-card p-8 rounded-[2.5rem] bg-gradient-to-br from-blue-600 to-indigo-700 text-white">
                <h3 class="text-xl font-bold mb-2">Platform Update Available</h3>
                <p class="text-blue-100 mb-6 text-sm">Version 4.2.0 includes enhanced security protocols and faster database indexing.</p>
                <button class="bg-white text-blue-600 px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:scale-105 transition">Update Now</button>
            </div>
        </section>

        <section id="analytics" class="view-section">
            <header class="mb-10">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Growth Analytics</h1>
                <p class="text-slate-500 font-medium">Visualizing user activity over time.</p>
            </header>

            <div class="glass-card p-8 rounded-[2rem] mb-6">
                <div class="flex items-center justify-between mb-8">
                    <h4 class="font-bold text-slate-700">User Growth (7 Days)</h4>
                    <span class="text-xs bg-blue-50 text-blue-600 px-3 py-1 rounded-full font-bold">Live Data</span>
                </div>
                <canvas id="growthChart" class="w-full" height="120"></canvas>
            </div>
        </section>

        <section id="users" class="view-section">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Client Directory</h1>
                <div class="flex gap-2">
                    <input type="text" placeholder="Search..." class="bg-white border-none rounded-xl px-4 py-2 text-sm shadow-sm focus:ring-2 focus:ring-blue-500">
                    <button class="bg-blue-600 text-white px-5 py-2 rounded-xl font-bold shadow-lg shadow-blue-100">Export</button>
                </div>
            </div>

            <div class="glass-card rounded-[2rem] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                            <tr>
                                <th class="p-6">Client Identity</th>
                                <th class="p-6">Registry Date</th>
                                <th class="p-6 text-right">Control</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php while($user = $users->fetch_assoc()): ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 bg-white border-2 border-slate-100 rounded-2xl flex items-center justify-center font-black text-blue-600 shadow-sm">
                                            <?php echo strtoupper(substr($user['full_name'], 0, 2)); ?>
                                        </div>
                                        <div>
                                            <p class="font-black text-slate-800"><?php echo htmlspecialchars($user['full_name']); ?></p>
                                            <p class="text-xs text-slate-400"><?php echo htmlspecialchars($user['email']); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6 text-sm text-slate-500 font-medium">
                                    <i class="far fa-calendar-check mr-2 opacity-30"></i><?php echo date('d M, Y', strtotime($user['created_at'])); ?>
                                </td>
                                <td class="p-6 text-right">
                                    <a href="?delete_id=<?php echo $user['id']; ?>" onclick="return confirm('Erase this record?')" class="h-10 w-10 bg-red-50 text-red-500 rounded-xl inline-flex items-center justify-center hover:bg-red-500 hover:text-white transition shadow-sm">
                                        <i class="fas fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>

    <script>
        // SIDEBAR TOGGLE
        const mobileBtn = document.getElementById('mobile-toggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        mobileBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        // VIEW NAVIGATION
        function showSection(sectionId, btn) {
            document.querySelectorAll('.view-section').forEach(s => s.classList.remove('active'));
            document.getElementById(sectionId).classList.add('active');

            document.querySelectorAll('.nav-btn').forEach(b => {
                b.classList.remove('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-100');
                b.classList.add('text-slate-400', 'hover:bg-slate-50');
            });

            btn.classList.add('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-100');
            btn.classList.remove('text-slate-400', 'hover:bg-slate-50');

            if (window.innerWidth < 768) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        // INITIALIZE CHART.JS (The Graph)
        const ctx = document.getElementById('growthChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'New Users',
                    data: [12, 19, 15, 25, 22, 30, 45],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false }, ticks: { font: { size: 10 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });
    </script>
</body>
</html>