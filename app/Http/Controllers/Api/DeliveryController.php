<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Delivery;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();

            $deliveries = Delivery::with(['sale', 'customer'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $data = $deliveries->map(function ($delivery) {
                return [
                    'id' => $delivery->id,
                    'sale_id' => $delivery->sale_id,
                    'user_id' => $delivery->user_id,
                    'customer_address' => $delivery->sale->customer_address ?? null,
                    'customer_firstName' => $delivery->sale->customer->first_name ?? null,
                    'customer_lastName' => $delivery->sale->customer->last_name ?? null,
                    'customer_phone' => $delivery->sale->customer->phone ?? null,
                    'status' => $delivery->status,
                    'created_at' => $delivery->created_at->toDateTimeString(),
                    'updated_at' => $delivery->updated_at->toDateTimeString(),
                ];
            });

            return response()->json([
                'success' => true,
                'driver_username' => $user->username,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch deliveries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function completeStatus()
    {
        $deliveries = Delivery::with(['sale', 'customer'])
            ->where('status', 'COMPLETE')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();


        if ($deliveries->count() > 0) {
            $data = $deliveries->map(function ($delivery) {
                return [
                    'id' => $delivery->id,
                    'sale_id' => $delivery->sale_id,
                    'user_id' => $delivery->user_id,
                    'customer_address' => $delivery->sale->customer_address ?? null,
                    'customer_firstName' => $delivery->sale->customer->first_name ?? null,
                    'customer_lastName' => $delivery->sale->customer->last_name ?? null,
                    'customer_phone' => $delivery->sale->customer->phone ?? null,
                    'status' => $delivery->status,
                    'created_at' => $delivery->created_at->toDateTimeString(),
                    'updated_at' => $delivery->updated_at->toDateTimeString(),
                ];
            });

            return response()->json(['data' => $data], 200);
        } else {
            return response()->json(['message' => 'No Records Available'], 200);
        }
    }
    
    public function ongoingDelivery()
    {
        $deliveries = Delivery::with(['sale', 'customer'])
            ->where('status', 'ONGOING')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();


        if ($deliveries->count() > 0) {
            $data = $deliveries->map(function ($delivery) {
                return [
                    'id' => $delivery->id,
                    'sale_id' => $delivery->sale_id,
                    'user_id' => $delivery->user_id,
                    'customer_address' => $delivery->sale->customer_address ?? null,
                    'customer_firstName' => $delivery->sale->customer->first_name ?? null,
                    'customer_lastName' => $delivery->sale->customer->last_name ?? null,
                    'customer_phone' => $delivery->sale->customer->phone ?? null,
                    'status' => $delivery->status,
                    'created_at' => $delivery->created_at->toDateTimeString(),
                    'updated_at' => $delivery->updated_at->toDateTimeString(),
                ];
            });

            return response()->json(['data' => $data], 200);
        } else {
            return response()->json(['message' => 'No Records Available'], 200);
        }
    }

    public function pendingDelivery()
    {
        $deliveries = Delivery::with('sale')
            ->where('status', 'PENDING')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($deliveries->count() > 0) {
            $data = $deliveries->map(function ($delivery) {
                return [
                    'id' => $delivery->id,
                    'sale_id' => $delivery->sale_id,
                    'user_id' => $delivery->user_id,
                    'customer_address' => $delivery->sale->customer_address ?? null,
                    'customer_firstName' => $delivery->sale->customer->first_name ?? null,
                    'customer_lastName' => $delivery->sale->customer->last_name ?? null,
                    'customer_phone' => $delivery->sale->customer->phone ?? null,
                    'status' => $delivery->status,
                    'created_at' => $delivery->created_at->toDateTimeString(),
                    'updated_at' => $delivery->updated_at->toDateTimeString(),
                ];
            });

            return response()->json(['data' => $data], 200);
        } else {
            return response()->json(['message' => 'No Records Available'], 200);
        }
    }

    public function historyDelivery()
    {
        $user = auth()->user();
        $query = Delivery::with(['sale.customer'])
            ->where('status', 'COMPLETE')
            ->orderBy('updated_at', 'desc');

        // Filter for drivers to only see their own deliveries
        if ($user->usertype === 'driver') {
            $query->where('user_id', $user->id);
        }

        $deliveries = $query->get();

        if ($deliveries->count() > 0) {
            $data = $deliveries->map(function ($delivery) {
                return [
                    'id' => $delivery->id,
                    'sale_id' => $delivery->sale_id,
                    'user_id' => $delivery->user_id,
                    'customer_address' => $delivery->sale->customer_address ?? null,
                    'customer_firstName' => $delivery->sale->customer->first_name ?? null,
                    'customer_lastName' => $delivery->sale->customer->last_name ?? null,
                    'customer_phone' => $delivery->sale->customer->phone ?? null,
                    'status' => $delivery->status,
                    'verification_photo' => $delivery->verification_photo_url,
                    'created_at' => $delivery->created_at->toDateTimeString(),
                    'updated_at' => $delivery->updated_at->toDateTimeString(),
                ];
            });

            return response()->json(['data' => $data], 200);
        }

        return response()->json(['message' => 'No Records Available'], 200);
    }

    public function acceptDelivery(Request $request)
    {
        $user = auth()->user();

        // Validate the incoming request
        $validatedData = $request->validate([
            'delivery_id' => 'required|exists:deliveries,id',
        ]);

        // Find the delivery record
        $delivery = Delivery::find($validatedData['delivery_id']);
        $customerFirstName = optional($delivery->sale->customer)->first_name ?? 'Unknown';
        $customerLastName = optional($delivery->sale->customer)->last_name ?? 'Unknown';
        // $customerAddress = optional($delivery->sale->customer)->address ?? 'Unknown Address';

        // Update the delivery details
        $delivery->update([
            'user_id' => $user->id,
            'status' => 'ONGOING',
        ]);

        // Update the related sale's status to ONGOING
        if ($delivery->sale_id) {
            Sale::where('id', $delivery->sale_id)->update(['status' => 'ONGOING']);
        }

        $usertype = $user->usertype;

        // Create an activity log
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Deliver',
            'description' => "{$user->username} ({$usertype} ID: $user->id) accepted delivery for {$customerFirstName} {$customerLastName}",
        ]);

        return response()->json([
            'message' => 'Delivery accepted successfully.',
            'delivery' => $delivery,
        ], 200);
    }

    public function completeDelivery(Request $request)
    {
        $user = auth()->user();

        // Validate request
        $validatedData = $request->validate([
            'delivery_id' => 'required|exists:deliveries,id',
            'verification' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Accept JPEG, PNG up to 2MB
        ]);

        // Find the delivery
        $delivery = Delivery::findOrFail($validatedData['delivery_id']);

        // Handle image upload
        if ($request->hasFile('verification')) {
            $image = $request->file('verification');

            // Generate custom filename
            $extension = $image->getClientOriginalExtension();
            $filename = "delivery_{$delivery->id}_verified_" . now()->format('Ymd_His') . '.' . $extension;

            $imagePath = $image->storeAs(
                'deliveries/verifications',
                $filename,
                'public'
            );
        } else {
            return response()->json(['error' => 'Verification image is required.'], 422);
        }

        // Update delivery
        $delivery->update([
            'user_id' => $user->id,
            'status' => 'COMPLETE',
            'verified' => 'YES',
            'verification' => $imagePath,
        ]);

        // Also mark sale as complete
        if ($delivery->sale_id) {
            Sale::where('id', $delivery->sale_id)->update(['status' => 'COMPLETE']);
        }

        $customerFirstName = optional($delivery->sale->customer)->first_name ?? 'Unknown';
        $customerLastName = optional($delivery->sale->customer)->last_name ?? 'Unknown';
        $customerAddress = optional($delivery->sale->customer)->address ?? 'Unknown Address';

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Complete Delivery',
            'description' => "{$user->username} ({$user->usertype} ID: $user->id) completed delivery for {$customerFirstName} {$customerLastName} at {$customerAddress}.",
        ]);

        return response()->json([
            'message' => 'Delivery marked as Completed Successfully.',
            'delivery' => $delivery,
        ], 200);
    }


    public function countPending()
    {
        try {
            $pendingCount = Delivery::where('status', 'PENDING')->count();

            return response()->json([
                'count' => $pendingCount
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch pending deliveries count.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
