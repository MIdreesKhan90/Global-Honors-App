<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $customer = Customer::find(Auth::user()->id);

        if ($customer) {
            switch ($customer->user_type) {
                case 0:
                    $customer->user_type = 'Trial';
                    break;
                case 1:
                    $customer->user_type = 'Standard';
                    break;
                case 2:
                    $customer->user_type = 'Premier';
                    break;
                case 3:
                    $customer->user_type = 'Internal';
                    break;
            }

        }

        if (!$customer->stripe_customer_id) {
            $subscriptionDetails = [];
        } else {
            // Get subscription data from stripe
            $stripe_key = env('STRIPE_SECRET');
            $stripe = new \Stripe\StripeClient($stripe_key);
            $stripeCustomer = $stripe->customers->retrieve($customer->stripe_customer_id, []);
            // Retrieve customer subscriptions
            $subscriptions = $stripe->subscriptions->all([
                'customer' => $stripeCustomer->id,
            ]);

            $subscriptionData = $subscriptions->data[0];
            if ($subscriptionData->plan instanceof \Stripe\Plan && isset($subscriptionData->plan->interval_count)) {
                if ($subscriptionData->plan->interval === 'year') {
                    $subscriptionEndDate = Carbon::createFromTimestamp($subscriptionData->current_period_end);
                    $nextBillingDateAfterEnd = $subscriptionEndDate->addDay();
                    $nextBillingDate = $nextBillingDateAfterEnd->format('M d, Y');
                }
                $subscriptionName = $subscriptionData->plan->nickname;
            }
            $latestInvoiceId = $subscriptionData->latest_invoice;
            $latestInvoice = $stripe->invoices->retrieve($latestInvoiceId, ['expand' => ['payment_intent']]);
            $paymentIntent = $latestInvoice->payment_intent;

            $cardDetails = $stripe->customers->retrievePaymentMethod(
                $customer->stripe_customer_id,
                $paymentIntent->source,
                []
            );
//        dd($cardDetails);
            if ($cardDetails && $cardDetails->card) {
                $cardDetail = $cardDetails->card;
            }

            $subscriptionDetails = [
                'plan_name' => $subscriptionName,
                'next_Billing_Date' => $nextBillingDate,
                'cardDetail' => $cardDetail
            ];

        }

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'customer' => $customer,
            'subscriptionDetails' => $subscriptionDetails
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $offers_notification = $request['offers_notification'] === true ? 1 : 0;
        $deadlines_notification = $request['deadlines_notification'] === true ? 1 : 0;
        $awards_notification = $request['awards_notification'] === true ? 1 : 0;

        $customer = Customer::find(Auth::id());
        $customer->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'offers_notification' => $offers_notification,
            'deadlines_notification' => $deadlines_notification,
            'awards_notification' => $awards_notification,
        ]);

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
