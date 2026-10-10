<?php
require_once __DIR__. '/../config/config.php';

if (!isset($_SESSION['user_id'])) {
    redirect(APP_URL. '/app/login.php');
    exit();
}
$user_name = $_SESSION['full_name']?? $_SESSION['username']?? 'Admin User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Pindari ERP</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f1f5f9]">
<div class="flex min-h-screen">
<div class="w-[240px] bg-[#0a2342] text-white flex flex-col p-4">
<div class="flex items-center gap-3 mb-8"><div class="bg-white text-[#c4121f] w-9 h-9 rounded flex items-center justify-center font-black">PE</div><div><p class="font-black text-[13px]">PINDARI</p><p class="text-[10px] opacity-70">ENTERPRISES</p></div></div>
<nav class="space-y-1 flex-1 text-[13px]">
<a class="flex items-center gap-3 bg-[#c4121f] p-2.5 rounded-lg font-bold"><i class="fa-solid fa-table-columns w-4"></i> Dashboard</a>
<a href="<?php echo APP_URL;?>/app/employees/" class="flex items-center gap-3 p-2.5 hover:bg-white/10 rounded-lg"><i class="fa-solid fa-users w-4"></i> Manpower</a>
<a href="<?php echo APP_URL;?>/app/placements/" class="flex items-center gap-3 p-2.5 hover:bg-white/10 rounded-lg"><i class="fa-solid fa-location-dot w-4"></i> Deployments</a>
<a href="<?php echo APP_URL;?>/app/clients/" class="flex items-center gap-3 p-2.5 hover:bg-white/10 rounded-lg"><i class="fa-solid fa-briefcase w-4"></i> Client Management</a>
<a class="flex items-center gap-3 p-2.5 hover:bg-white/10 rounded-lg"><i class="fa-solid fa-wallet w-4"></i> Payroll</a>
<a class="flex items-center gap-3 p-2.5 hover:bg-white/10 rounded-lg"><i class="fa-solid fa-chart-simple w-4"></i> Reports</a>
</nav>
<a href="logout.php" class="mt-4 text-xs opacity-60 hover:opacity-100"><i class="fa-solid fa-right-from-bracket mr-2"></i>Logout</a>
</div>

<div class="flex-1">
<div class="bg-[#0a2342] text-white flex justify-between items-center px-6 py-3">
<div class="flex gap-6 text-[13px]"><span class="border-b-2 border-[#c4121f] pb-1 font-bold">Dashboard</span><span class="opacity-60">Manpower</span><span class="opacity-60">Deployments</span><span class="opacity-60">Clients</span></div>
<div class="flex items-center gap-4 text-sm"><span class="text-[13px]"><?php echo htmlspecialchars($user_name);?></span><i class="fa-regular fa-bell opacity-70"></i></div>
</div>

<div class="p-6">
<div class="flex justify-between items-start mb-6"><div><h1 class="text-[28px] font-black text-[#0a2342]">Dashboard Overview</h1><p class="text-[13px] text-gray-500">Welcome back, <?php echo htmlspecialchars($user_name);?> — here's your manpower deployment overview</p></div>
<div class="flex gap-3"><button class="bg-white border px-4 py-2 rounded-lg text-[12px] font-bold">Export Report</button><button class="bg-[#c4121f] text-white px-4 py-2 rounded-lg text-[12px] font-bold">New Deployment</button></div></div>

<div class="grid grid-cols-4 gap-4 mb-6">
<div class="bg-white p-5 rounded-xl shadow-sm border"><div class="w-10 h-10 bg-[#c4121f] rounded-full flex items-center justify-center text-white mb-3"><i class="fa-solid fa-users"></i></div><p class="text-[12px] font-bold text-gray-600">Total Manpower</p><p class="text-[32px] font-black text-[#0a2342]">1,248</p><p class="text-[11px] text-emerald-600 font-bold">+52 from last month</p></div>
<div class="bg-white p-5 rounded-xl shadow-sm border"><div class="w-10 h-10 bg-[#c4121f] rounded-full flex items-center justify-center text-white mb-3"><i class="fa-solid fa-location-dot"></i></div><p class="text-[12px] font-bold">Active Deployments</p><p class="text-[32px] font-black">87</p><p class="text-[11px] text-emerald-600 font-bold">+8 this week</p></div>
<div class="bg-white p-5 rounded-xl shadow-sm border"><div class="w-10 h-10 bg-[#c4121f] rounded-full flex items-center justify-center text-white mb-3"><i class="fa-solid fa-clock"></i></div><p class="text-[12px] font-bold">Pending Requests</p><p class="text-[32px] font-black">14</p><p class="text-[11px] text-orange-500 font-bold">-3 from yesterday</p></div>
<div class="bg-white p-5 rounded-xl shadow-sm border"><div class="w-10 h-10 bg-[#c4121f] rounded-full flex items-center justify-center text-white mb-3"><i class="fa-solid fa-chart-line"></i></div><p class="text-[12px] font-bold">Utilization Rate</p><p class="text-[32px] font-black">92.4%</p><p class="text-[11px] text-emerald-600 font-bold">+1.2% on target</p></div>
</div>

<div class="grid grid-cols-2 gap-4">
<div class="bg-white p-5 rounded-xl shadow-sm border"><h3 class="font-black text-[#0a2342]">Deployment Mapping by Region</h3><p class="text-[11px] text-gray-400 mb-4">Active deployments across India</p>
<div class="space-y-3 text-[12px] font-bold"><div class="flex items-center"><span class="w-28">Maharashtra</span><div class="bg-[#c4121f] text-white px-2 py-1 w-[180px] text-right">28</div></div><div class="flex items-center"><span class="w-28">Delhi</span><div class="bg-[#0a2342] text-white px-2 py-1 w-[140px] text-right">22</div></div><div class="flex items-center"><span class="w-28">Karnataka</span><div class="bg-[#c4121f] text-white px-2 py-1 w-[110px] text-right">16</div></div><div class="flex items-center"><span class="w-28">Gujarat</span><div class="bg-[#0a2342] text-white px-2 py-1 w-[80px] text-right">12</div></div><div class="flex items-center"><span class="w-28">Tamil Nadu</span><div class="bg-[#c4121f] text-white px-2 py-1 w-[55px] text-right">9</div></div></div>
</div>
<div class="bg-white p-5 rounded-xl shadow-sm border"><h3 class="font-black text-[#0a2342]">Recent Activity</h3><p class="text-[11px] text-gray-400 mb-4">Live updates</p>
<div class="space-y-3 text-[12px]"><div><p>25 Manpower deployed to - Maruti Suzuki plant <span class="bg-[#c4121f] text-white text-[10px] px-2 py-0.5 rounded-full float-right">Deployed</span></p><p class="text-[11px] text-gray-400">Today, 10:32 AM</p></div><div><p>New hiring request from - Tata Motors, Pune <span class="bg-yellow-400 text-[10px] px-2 py-0.5 rounded-full float-right">Pending</span></p><p class="text-[11px] text-gray-400">Today, 09:15 AM</p></div><div><p>Training completed - 40 workers <span class="bg-blue-500 text-white text-[10px] px-2 py-0.5 rounded-full float-right">Training</span></p><p class="text-[11px] text-gray-400">Yesterday, 02:00 PM</p></div></div>
</div>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
