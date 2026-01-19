<?php

namespace App\Http\Controllers\Admin;

use App\Models\AffiliateWithdrawal;
use App\Enums\WithdrawalStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AffiliateService;

class AffiliateWithdrawalController extends Controller
{
    public function __construct(private AffiliateService $affiliateService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $withdrawals = AffiliateWithdrawal::with('user')
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('keyword'), function ($q) use ($request) {
                $keyword = $request->keyword;
                $q->where(function ($query) use ($keyword) {
                    $query->where('amount', 'LIKE', "%{$keyword}%")
                        ->orWhere('method', 'LIKE', "%{$keyword}%")
                        ->orWhere('account_info', 'LIKE', "%{$keyword}%")
                        ->orWhereHas('user', function ($userQuery) use ($keyword) {
                            $userQuery->where('name', 'LIKE', "%{$keyword}%")
                                ->orWhere('email', 'LIKE', "%{$keyword}%");
                        });
                });
            })
            ->latest()
            ->paginate(20);

        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected'
        ];

        return view('admin.pages.affiliate-withdrawal.index', compact('withdrawals', 'statuses'));
    }

    /**
     * Display the specified resource.
     */
    public function show(AffiliateWithdrawal $affiliateWithdrawal)
    {
        $affiliateWithdrawal->load('user');
        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected'
        ];

        return view('admin.pages.affiliate-withdrawal.show', compact('affiliateWithdrawal', 'statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AffiliateWithdrawal $affiliateWithdrawal)
    {
        $affiliateWithdrawal->load('user');
        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected'
        ];

        return view('admin.pages.affiliate-withdrawal.edit', compact('affiliateWithdrawal', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AffiliateWithdrawal $affiliateWithdrawal)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'note' => 'nullable|string|max:500'
        ]);

        $oldStatus = $affiliateWithdrawal->status;
        $newStatus = $request->status;

        $affiliateWithdrawal->update([
            'status' => $newStatus,
            'note' => $request->note
        ]);

        // Handle status changes
        if ($oldStatus === 'pending' && $newStatus === 'approved') {
            try {
                $this->affiliateService->processWithdrawal($affiliateWithdrawal);
            } catch (\Exception $e) {
                flashMessage('error', 'Withdrawal approved but processing failed: ' . $e->getMessage());
                return redirect()->back();
            }
        } elseif ($oldStatus === 'pending' && $newStatus === 'rejected') {
            try {
                $this->affiliateService->rejectWithdrawal($affiliateWithdrawal);
            } catch (\Exception $e) {
                flashMessage('error', 'Withdrawal rejected but refund failed: ' . $e->getMessage());
                return redirect()->back();
            }
        }

        // Send email notification to user about status change
        $this->affiliateService->sendWithdrawalStatusChangeEmail($affiliateWithdrawal, $oldStatus, $newStatus);

        flashMessage('success', 'Withdrawal status updated successfully');
        return redirect()->route('admin.affiliate-withdrawal.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AffiliateWithdrawal $affiliateWithdrawal)
    {
        // Only allow deletion of pending withdrawals
        if ($affiliateWithdrawal->status !== 'pending') {
            flashMessage('error', 'Only pending withdrawals can be deleted');
            return redirect()->back();
        }

        $affiliateWithdrawal->delete();
        flashMessage('success', 'Withdrawal request deleted successfully');
        return redirect()->back();
    }
}
