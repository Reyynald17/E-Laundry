<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['customer', 'services'])->latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'Daftar transaksi laundry berhasil dimuat',
            'data' => OrderResource::collection($orders)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi langsung di sini agar bebas dari error StoreOrderRequest / 403 Forbidden
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'completion_date' => 'required|date|after_or_equal:today',
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.qty' => 'required|integer|min:1',
        ], [
            'customer_id.required' => 'Pelanggan wajib dipilih.',
            'customer_id.exists' => 'Data pelanggan tidak ditemukan dalam database.',
            'completion_date.required' => 'Tanggal estimasi selesai wajib diisi.',
            'completion_date.after_or_equal' => 'Tanggal estimasi tidak boleh sebelum hari ini.',
            'services.required' => 'Minimal pilih 1 layanan laundry.',
            'services.array' => 'Format data layanan tidak valid.',
            'services.*.service_id.required' => 'Layanan laundry wajib dipilih.',
            'services.*.service_id.exists' => 'Layanan yang dipilih tidak terdaftar.',
            'services.*.qty.required' => 'Jumlah kuantitas/berat wajib diisi.',
            'services.*.qty.min' => 'Jumlah kuantitas/berat minimal 1.',
        ]);

        DB::beginTransaction();
        try {
            $totalPrice = 0;
            
            // 1. Buat kepala transaksi (orders)
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'invoice_code' => 'INV-' . date('Ymd') . '-' . rand(100, 999),
                'order_date' => now(),
                'completion_date' => $request->completion_date,
                'status' => 'pending',
                'total_price' => 0
            ]);

            // 2. Iterasi dan simpan detail layanan ke pivot table (order_details)
            foreach ($request->services as $item) {
                $service = Service::findOrFail($item['service_id']);
                $subtotal = $service->price_per_kg * $item['qty'];
                $totalPrice += $subtotal;
                
                $order->services()->attach($service->id, [
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal
                ]);
            }

            // 3. Update total_price di tabel orders
            $order->update(['total_price' => $totalPrice]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaksi laundry berhasil dibuat',
                'data' => new OrderResource($order->load(['customer', 'services']))
            ], 201); 

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memproses transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['customer', 'services'])->find($id);
        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi laundry tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail transaksi berhasil ditemukan',
            'data' => new OrderResource($order)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Update status order.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,ready,completed'
        ]);

        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi laundry tidak ditemukan'
            ], 404);
        }

        $order->update(['status' => $request->status]);

        return response()->json([
            'status' => true,
            'message' => 'Status laundry berhasil diperbarui',
            'data' => new OrderResource($order->load(['customer', 'services']))
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}