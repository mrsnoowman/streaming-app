<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cctv;
use App\Models\Vms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = 10;
            $page = $request->get('page', 1);
            
            // Cache key untuk pagination
            $cacheKey = "cctv_page_{$page}";
            
            // Query CCTV dengan pagination dan caching
            $cctvs = Cache::remember($cacheKey, 300, function () use ($perPage, $page) {
                return Cctv::orderBy('lokasi', 'asc')
                    ->paginate($perPage, ['*'], 'page', $page)
                    ->appends(request()->query());
            });
            
            return view('cctv', compact('cctvs'));
            
        } catch (\Exception $e) {
            // Fallback ke data kosong jika ada error koneksi database
            $cctvs = collect([]);
            
            // Log error untuk debugging
            \Log::error('Database connection error: ' . $e->getMessage());
            
            return view('cctv', compact('cctvs'))->with('error', 'Database connection error. Please check your database configuration.');
        }
    }

    public function vms(Request $request)
    {
        try {
            $perPage = 10;
            $page = $request->get('page', 1);
            
            // Cache key untuk pagination
            $cacheKey = "vms_page_{$page}";
            
            // Query VMS dengan pagination dan caching
            $vms = Cache::remember($cacheKey, 300, function () use ($perPage, $page) {
                return Vms::orderBy('lokasi', 'asc')
                    ->paginate($perPage, ['*'], 'page', $page)
                    ->appends(request()->query());
            });
            
            return view('vms', compact('vms'));
            
        } catch (\Exception $e) {
            // Fallback ke data kosong jika ada error koneksi database
            $vms = collect([]);
            
            // Log error untuk debugging
            \Log::error('Database connection error: ' . $e->getMessage());
            
            return view('vms', compact('vms'))->with('error', 'Database connection error. Please check your database configuration.');
        }
    }
}
