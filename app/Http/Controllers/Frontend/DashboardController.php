<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Blog;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Services\AffiliateService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function myAccount()
    {
        $user = auth()->user();

        return view('frontend.pages.user.profile', compact('user'));
    }

    public function dashboard()
    {
        $user = auth()->user();
        $order_count = $user?->orders?->count();
        $total_spent = $user?->orders?->sum('total');
        $pending_order_count = $user?->orders()?->where('status', 1)->count();

        return view('frontend.pages.user.dashboard', compact('user', 'order_count', 'total_spent', 'pending_order_count'));
    }

    public function affiliateDashboard()
    {
        $user = auth()->user();
        $settings = Setting::first();
        if (!$settings || !$settings->affiliate_feature_enabled) {
            abort(404);
        }

        // affiliate
        if ($user?->is_affiliate) {
            $affiliateService = new AffiliateService();
            $user = $affiliateService->ensureUserHasCode($user);
            $link = $affiliateService->buildAffiliateLink($user->affiliate_code);
            $settings = Setting::select(['affiliate_commission_percent'])->first();
            return view('frontend.pages.affiliate.dashboard', compact('user', 'link', 'settings'));
        } else {
            abort(403);
        }
    }

    public function profile()
    {
        $user = auth()->user()->load('address');
        return view('frontend.pages.user.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'avatar' => 'nullable|image',
            'phone_number' => 'required',
            'address' => 'required',
        ]);
        $user = $request->user();
        $avatar = $user->avatar;
        if ($request->hasFile('avatar')) {
            deleteFile($avatar);
            $avatar = uploadImage($request->avatar, 'avatar');
        }
        $user->update([
            'name' => $request->name,
            'avatar' => $avatar,
        ]);
        $user->address()->updateOrCreate(
            [],
            [
                'name' => $user->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
            ]
        );

        flashMessage('success', 'Profile updated');
        return redirect()->back();
    }


    public function password()
    {
        $user = auth()->user();

        return view('frontend.pages.user.password', compact('user'));
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors('old_password', 'Old password not matched.');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        flashMessage('success', 'Password updated');
        return redirect()->back();
    }

    public function order()
    {
        $user = auth()->user();
        $orders = $user->orders()->latest()->paginate(20);
        return view('frontend.pages.user.order', compact('user', 'orders'));
    }

    public function orderShow(Order $order)
    {
        $order->load('items');
        $user = auth()->user();

        return view('frontend.pages.user.order-show', compact('user', 'order'));
    }

    public function orderTrack(Request $request)
    {
        $user = auth()->user();
        $order = null;
        $orderId = $request->input('order_id');

        if ($orderId) {
            // Try to find order by hashed_id first for authenticated user
            $order = Order::where('hashed_id', $orderId)
                ->where('user_id', $user->id)
                ->with(['items.product'])
                ->first();

            // If not found, return null
            if (!$order) {
                $orderId = null;
            }
        }

        return view('frontend.pages.user.order-track', compact('user', 'order', 'orderId'));
    }

    public function publicOrderTrack(Request $request)
    {
        $order = null;
        $orderId = $request->input('order_id');

        if ($orderId) {
            // Try to find order by hashed_id first
            $order = Order::where('hashed_id', $orderId)
                ->with(['items.product'])
                ->first();

            // If not found, return null
            if (!$order) {
                $orderId = null;
            }
        }

        return view('frontend.pages.public.order-track', compact('order', 'orderId'));
    }

    public function wishlist()
    {
        $user = auth()->user();
        $favorites = $user->favorites()->latest()->paginate(15);

        return view('frontend.pages.user.wishlist', compact('user', 'favorites'));
    }

    public function toggleWishlist(Request $request)
    {
        $request->validate([
            'product' => 'required'
        ]);

        $user = $request->user();

        // scene 1 -> if exist then remove it
        $item_exist = $user->favorites()->where('product_id', $request->product)->first();

        if ($item_exist) {
            $user->favorites()->detach($request->product);
            flashMessage('success', 'Product successfully remove from favorite list.');
            return redirect()->back()->with('scroll_position', $request->scroll_position);
        }

        // scene 2 => add
        $user->favorites()->attach($request->product);

        flashMessage('success', 'Product successfully added in your favorite list.');
        return redirect()->back()->with('scroll_position', $request->scroll_position);
    }

    public function notification()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->paginate(20);

        return view('frontend.pages.user.notification', compact('user', 'notifications'));
    }

    public function address()
    {
        $user = auth()->user();
        $address = $user->address;

        return view('frontend.pages.user.address', compact('user', 'address'));
    }

    public function addressStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        $user = auth()->user();
        $address = $user->address;

        if ($address) {
            $address->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
            ]);
        } else {
            $user->address()->create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
            ]);
        }

        flashMessage('success', 'Updated');
        return redirect()->back();
    }

    public function transaction()
    {
        $user = auth()->user();

        return view('frontend.pages.user.transaction', compact('user'));
    }

    public function offer()
    {
        $user = auth()->user();

        return view('frontend.pages.user.offer', compact('user'));
    }

    public function referral()
    {
        $user = auth()->user();

        return view('frontend.pages.user.referral', compact('user'));
    }

    public function tips()
    {
        $user = auth()->user();
        $blogs = Blog::latest()->paginate(20);

        return view('frontend.pages.user.tips', compact('user', 'blogs'));
    }

    public function rateUs()
    {
        $user = auth()->user();
        $ratings = $user->ratings()->latest()->get();

        return view('frontend.pages.user.rate-us', compact('user', 'ratings'));
    }

    public function rateUsStore(Request $request)
    {
        $request->validate([
            'rating' => 'required'
        ]);

        Testimonial::create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'description' => $request->description
        ]);

        flashMessage('success', 'Rating submitted');
        return redirect()->back();
    }

    public function menu()
    {
        return view('frontend.pages.user.menu');
    }
}
