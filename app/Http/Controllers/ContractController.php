<?php

namespace App\Http\Controllers;

use App\Models\Commodity;
use App\Models\PreOrderContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ContractController extends Controller
{
    /**
     * Atomic smart pre-order contract submission.
     * Uses DB transaction and row locking to guarantee capacity availability.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'commodity_id' => ['required', 'exists:commodities,id'],
            'buyer_type' => ['required', 'in:MBG_Unit,Corporate'],
            'buyer_name' => ['required', 'string', 'max:255'],
            'volume_requested' => ['required', 'integer', 'min:1'],
            'delivery_target_date' => ['required', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $contract = DB::transaction(function () use ($validated) {
                // Lock the commodity row atomically to prevent race conditions
                $commodity = Commodity::where('id', $validated['commodity_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                // Prevent execution if capacity is insufficient
                if ($commodity->current_capacity < $validated['volume_requested']) {
                    throw ValidationException::withMessages([
                        'volume_requested' => [
                            "Kapasitas pasokan tidak mencukupi. Sisa kapasitas tersedia saat ini: {$commodity->current_capacity} {$commodity->unit}."
                        ]
                    ]);
                }

                // Deduct volume safely from current_capacity
                $commodity->decrement('current_capacity', $validated['volume_requested']);

                // Generate unique smart contract number
                $contractNumber = 'DSH-MBG-' . strtoupper(substr(md5(uniqid()), 0, 8));

                // Create the PreOrderContract record
                return PreOrderContract::create([
                    'commodity_id' => $commodity->id,
                    'buyer_type' => $validated['buyer_type'],
                    'buyer_name' => $validated['buyer_name'],
                    'volume_requested' => $validated['volume_requested'],
                    'agreed_price' => $commodity->base_price_b2b,
                    'contract_status' => 'Active',
                    'delivery_target_date' => $validated['delivery_target_date'],
                    'contract_number' => $contractNumber,
                    'notes' => $validated['notes'] ?? null,
                ]);
            });

            return redirect()->route('contract.show', $contract->id)
                ->with('success', 'Kontrak Pre-Order berhasil dikunci secara atomik!');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal mengunci kontrak pre-order: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Display the professional B2B invoice & smart contract terms.
     */
    public function show(int $id): Response
    {
        $contract = PreOrderContract::with(['commodity.cooperative'])
            ->findOrFail($id);

        return Inertia::render('ContractDetail', [
            'contract' => $contract,
        ]);
    }
}
