<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Pindari ERP</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-[#f1f5f9]">
<div class="flex min-h-screen">
<!-- SIDEBAR -->
<div class="w-[240px] bg-[#0a2342] text-white flex flex-col p-4">
<div class="flex items-center gap-3 mb-8">
<div class="bg-white text-[#c4121f] w-9 h-9 rounded flex items-center justify-center font-black">PE</div>
<div><p class="font-black text-[13px] leading-none">PINDARI</p><p class="text-[10px] tracking-widest opacity-70">ENTERPRISES</p></div>
</div>
<nav class="space-y-1 flex-1 text-[13px]">
<a class="flex items-center gap-3 bg-[#c4121f] p-2.5 rounded-lg font-bold"><i class="fa-solid fa-table-columns w-4"></i> Dashboard</a>
<a class="flex items-center gap-3 p-2.5 hover:bg-white/10 rounded-lg"><i class="fa-solid fa-users w-4"></i> Manpower</a>
<a class="flex items-center gap-3 p-2.5 hover:bg-white/10 rounded-lg"><i class="fa-solid fa-location-dot w-4"></i> Deployments</a>
<a class="flex items-center gap-3 p-2.5 hover:bg-white/10 rounded-lg"><i class="fa-solid fa-briefcase w-4"></i> Client Management</a>
<a class="flex items-center gap-3 p-2.5 hover:bg-white/10 rounded-lg"><i class="fa-solid fa-user-plus w-4"></i> Recruitment</a>
<a class="flex items-center gap-3 p-2.5 hover:bg-white/10 rounded-lg"><i class="fa-solid fa-file-shield w-4"></i> Training & Compliance</a>
<a class="flex items-center gap-3 p-2.5 hover:bg-white/10 rounded-lg"><i class="fa-solid fa-wallet w-4"></i> Payroll</a>
<a class="flex items-center gap-3 p-2.5 hover:bg-white/10 rounded-lg"><i class="fa-solid fa-chart-simple w-4"></i> Reports & Analytics</a>
<a class="flex items-center gap-3 p-2.5 hover:bg-white/10 rounded-lg"><i class="fa-solid fa-gear w-4"></i> Settings</a>
</nav>
<div class="border border-[#c4121f]/50 rounded-lg p-3 mt-4">
<p class="text-[10px] font-black leading-tight">TRUSTED MANPOWER SUPPLY PARTNER</p>
<p class="text-[9px] opacity-60 mt-1">Connecting Talent. Building Nation.</p>
</div>
<a href="logout.php" class="mt-4 text-xs opacity-60 hover:opacity-100"><i class="fa-solid fa-right-from-bracket mr-2"></i>Logout</a>
</div>

<!-- MAIN -->
<div class="flex-1">
<!-- TOP NAV -->
<div class="bg-[#0a2342] text-white flex justify-between items-center px-6 py-3">
<div class="flex gap-6 text-[13px]">
<span class="border-b-2 border-[#c4121f] pb-1 font-bold">Dashboard</span>
<span class="opacity-60">Manpower</span><span class="opacity-60">Deployments</span><span class="opacity-60">Clients</span><span class="opacity-60">Payroll</span><span class="opacity-60">Reports</span><span class="opacity-60">Settings</span>
</div>
<div class="flex items-center gap-4 text-sm">
<div class="w-8 h-8 border border-white/30 rounded-full flex items-center justify-center text-[10px]">AD</div>
<span class="text-[13px]"><?php echo $_SESSION['user'];?> <i class="fa-solid fa-caret-down ml-1 text-[10px]"></i></span>
<i class="fa-regular fa-bell opacity-70"></i><i class="fa-solid fa-gear opacity-70"></i>
</div>
</div>

<!-- CONTENT -->
<div class="p-6">
<div class="flex justify-between items-start mb-6">
<div><h1 class="text-[28px] font-black text-[#0a2342] leading-none">Dashboard Overview</h1><p class="text-[13px] text-gray-500 mt-1">Welcome back, <?php echo $_SESSION['user'];?> — here's your manpower deployment overview for Oct 2024</p></div>
<div class="flex gap-3">
<button class="bg-white border px-4 py-2 rounded-lg text-[12px] font-bold shadow-sm"><i class="fa-solid fa-download mr-2"></i>Export Report</button>
<button class="bg-[#c4121f] text-white px-4 py-2 rounded-lg text-[12px] font-bold shadow"><i class="fa-solid fa-plus mr-2"></i>New Deployment</button>
</div>
</div>

<!-- 4 CARDS -->
<div class="grid grid-cols-4 gap-4 mb-6">
<div class="bg-white p-5 rounded-xl shadow-sm border"><div class="w-10 h-10 bg-[#c4121f] rounded-full flex items-center justify-center text-white mb-3"><i class="fa-solid fa-users text-sm"></i></div><p class="text-[12px] font-bold text-gray-600">Total Manpower</p><p class="text-[32px] font-black text-[#0a2342] leading-none my-1">1,248</p><p class="text-[11px] text-emerald-600 font-bold"><i class="fa-solid fa-caret-up"></i> +52 from last month</p></div>
<div class="bg-white p-5 rounded-xl shadow-sm border"><div class="w-10 h-10 bg-[#c4121f] rounded-full flex items-center justify-center text-white mb-3"><i class="fa-solid fa-location-dot text-sm"></i></div><p class="text-[12px] font-bold text-gray-600">Active Deployments</p><p class="text-[32px] font-black text-[#0a2342] leading-none my-1">87</p><p class="text-[11px] text-emerald-600 font-bold"><i class="fa-solid fa-caret-up"></i> +8 this week</p></div>
<div class="bg-white p-5 rounded-xl shadow-sm border"><div class="w-10 h-10 bg-[#c4121f] rounded-full flex items-center justify-center text-white mb-3"><i class="fa-solid fa-clock text-sm"></i></div><p class="text-[12px] font-bold text-gray-600">Pending Requests</p><p class="text-[32px] font-black text-[#0a2342] leading-none my-1">14</p><p class="text-[11px] text-orange-500 font-bold"><i class="fa-solid fa-caret-down"></i> -3 from yesterday</p></div>
<div class="bg-white p-5 rounded-xl shadow-sm border"><div class="w-10 h-10 bg-[#c4121f] rounded-full flex items-center justify-center text-white mb-3"><i class="fa-solid fa-chart-line text-sm"></i></div><p class="text-[12px] font-bold text-gray-600">Utilization Rate</p><p class="text-[32px] font-black text-[#0a2342] leading-none my-1">92.4%</p><p class="text-[11px] text-emerald-600 font-bold"><i class="fa-solid fa-caret-up"></i> +1.2% on target</p></div>
</div>

<div class="grid grid-cols-2 gap-4">
<div class="bg-white p-5 rounded-xl shadow-sm border"><h3 class="font-black text-[16px] text-[#0a2342]">Deployment Mapping by Region</h3><p class="text-[11px] text-gray-400 mb-5">Active deployments across India</p>
<div class="space-y-3 text-[12px] font-bold">
<div class="flex items-center"><span class="w-28">Maharashtra</span><div class="bg-[#c4121f] text-white px-2 py-1 text-right w-[180px]">28</div></div>
<div class="flex items-center"><span class="w-28">Delhi</span><div class="bg-[#0a2342] text-white px-2 py-1 text-right w-[140px]">22</div></div>
<div class="flex items-center"><span class="w-28">Karnataka</span><div class="bg-[#c4121f] text-white px-2 py-1 text-right w-[110px]">16</div></div>
<div class="flex items-center"><span class="w-28">Gujarat</span><div class="bg-[#0a2342] text-white px-2 py-1 text-right w-[80px]">12</div></div>
<div class="flex items-center"><span class="w-28">Tamil Nadu</span><div class="bg-[#c4121f] text-white px-2 py-1 text-right w-[55px]">9</div></div>
</div>
<div class="flex gap-4 mt-5 text-[11px] font-bold"><span><span class="w-2 h-2 bg-[#c4121f] inline-block rounded-full mr-1"></span> West & South</span><span><span class="w-2 h-2 bg-[#0a2342] inline-block rounded-full mr-1"></span> North & Central</span></div>
</div>

<div class="bg-white p-5 rounded-xl shadow-sm border"><h3 class="font-black text-[16px] text-[#0a2342]">Recent Activity</h3><p class="text-[11px] text-gray-400 mb-5">Live updates</p>
<div class="space-y-4 text-[12px]">
<div class="border-b pb-3"><p><span class="w-2 h-2 bg-[#c4121f] inline-block rounded-full mr-1"></span> 25 Manpower deployed to - Maruti Suzuki plant, Gurugra <span class="bg-[#c4121f] text-white text-[10px] px-2 py-0.5 rounded-full float-right">Deployed</span></p><p class="text-[11px] text-gray-400 ml-3 mt-1">Today, 10:32 AM</p></div>
<div class="border-b pb-3"><p><span class="w-2 h-2 bg-[#0a2342] inline-block rounded-full mr-1"></span> New hiring request from - Tata Motors, Pune <span class="bg-yellow-400 text-[10px] px-2 py-0.5 rounded-full float-right">Pending</span></p><p class="text-[11px] text-gray-400 ml-3 mt-1">Today, 09:15 AM &nbsp;&nbsp; Yesterday, 04:20 PM</p></div>
<div class="border-b pb-3"><p><span class="w-2 h-2 bg-[#c4121f] inline-block rounded-full mr-1"></span> Training completed - 40 workers, Safety Module <span class="bg-blue-500 text-white text-[10px] px-2 py-0.5 rounded-full float-right">Training</span></p><p class="text-[11px] text-gray-400 ml-3 mt-1">Yesterday, 02:00 PM</p></div>
<div><p><span class="w-2 h-2 bg-[#0a2342] inline-block rounded-full mr-1"></span> Invoice #PE1024 approved - Reliance Industries <span class="bg-emerald-500 text-white text-[10px] px-2 py-0.5 rounded-full float-right">Paid</span></p><p class="text-[11px] text-gray-400 ml-3 mt-1">Oct 26, 2024</p></div>
</div>
</div>
</div>
</div>
<div class="text-center py-3 text-[11px] font-bold text-gray-500 bg-[#e2e8f0] mt-6">PINDARI ENTERPRISES ERP v2.1 | © 2024 Pindari Enterprises. All rights reserved.</div>
</div>
</div>
</body>
</html>
