<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Pricing;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Stripe\StripeClient;

class PricingController extends Controller
{

    private $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function index()
    {

        $pricing = Pricing::select(
            'page_title',
            'page_desc',
            'first_plan_name',
            'first_plan_title',
            'first_plan_desc',
            'first_plan_list_items',
            'first_plan_button_title',
            'first_plan_button_link',
            'second_plan_name',
            'second_plan_title',
            'second_plan_desc',
            'second_plan_list_items',
            'second_plan_button_title',
            'second_plan_button_link',
            'is_premier_promo',
            'premier_promo_badge',
            'premier_promo_label'
        )->first();

        return Inertia::render('Plans', [
            'pricing' => $pricing
        ]);
    }

    public function getPlanPricing($plan)
    {

    }

    public function checkoutIndex(Request $request)
    {
        $requestedData = $request->all();

        $planId = null;
        $data = null;
        $totalPrice = null;

        $planId = env('STRIPE_FIRST_PLAN_PRICE');


        if ($planId) {

            $promoCodes = [
                'earlybirdfirsttime', // Your default promo code
            ];

// Add the promo code from the request if it exists
            if ($request->input('promotion_code')) {
                $promoCodes[] = $request->input('promotion_code');
            }

            // Check if a request is made to remove a promo code
            if ($request->input('remove_promotion_code')) {
                // Remove the specified promo code from the array
                $promoCodes = array_filter($promoCodes, function ($code) use ($request) {
                    return $code !== $request->input('remove_promotion_code');
                });
            }

            $retrievedPromoCodes = [];
            $error_messages = [];
            $plan = $this->stripe->plans->retrieve($planId);
            $product = $this->stripe->products->retrieve($plan->product);
            $totalPriceCents = $plan->amount;
            $formattedSubTotal = number_format($plan->amount / 100, 2, '.', ',');

            foreach ($promoCodes as $code) {
                try {
                    $promotionCodeDetails = $this->stripe->promotionCodes->all([
                        'code' => $code,
                    ])->data;

                    if (!empty($promotionCodeDetails)) {
                        // Assuming you want the first match since codes should be unique
                        $promoCode = $promotionCodeDetails[0];

                        // Check if the promotion code is valid and has not expired
                        if ($promoCode->coupon->valid) {
                            if ($promoCode->coupon->percent_off) {
                                $totalPriceCents = $totalPriceCents * (1 - ($promoCode->coupon->percent_off / 100));
                            } elseif ($promoCode->coupon->amount_off) {
                                $totalPriceCents -= $promoCode->coupon->amount_off;
                            }
                        }

                        $retrievedPromoCodes[] = $promoCode;
                    } else {
                        $error_messages[$code] = 'No promotion code found with that code.';
                    }
                } catch (\Exception $e) {
                    $error_messages[$code] = 'Error retrieving promotion code: ' . $e->getMessage();
                }
            }

// After all promo codes have been applied, format the total price
// Convert cents to dollars for display
            $totalPriceDollars = $totalPriceCents / 100;

// Apply conditional formatting based on the total price
            $formattedTotalPrice = $totalPriceDollars >= 1000 ?
                number_format($totalPriceDollars, 2, '.', ',') :
                number_format($totalPriceDollars, 2, '.', '');

// Package the data for the view
            $data = array(
                'subTotal' => '$' . $formattedSubTotal,
                'planName' => $product->name,
                'promotionCodes' => $retrievedPromoCodes, // Include the applied promotion codes
                'errorMessages' => $error_messages, // Include any error messages
            );

            $totalPrice = '$' . $formattedTotalPrice;
        }


        return Inertia::render('Checkout', compact('data', 'totalPrice'));

    }

    public function calculatePrice($promotion_code, $calculatedPrice)
    {
        $error_message = '';

        $planId = env('STRIPE_FIRST_PLAN_PRICE');
        $promoCode = $promotion_code;

        // Retrieve the plan to get its price
        $plan = $this->stripe->plans->retrieve($planId);

        // Initialize total price with the full amount of the plan
        $totalPrice = $calculatedPrice;
        // Apply promotion code if provided
        if ($promoCode) {
            try {
                $promotionCodeDetails = $this->stripe->promotionCodes->all([
                    'code' => $promoCode,
                ])->data;

                if (count($promotionCodeDetails) > 0) {
                    $promotionCode = $promotionCodeDetails[0]; // Take the first one if there are multiple

                    if ($promotionCode->coupon->valid && $promotionCode->coupon->percent_off) {
                        $totalPrice *= (1 - ($promotionCode->coupon->percent_off / 100));
                    }

                    if ($promotionCode->coupon->valid && $promotionCode->coupon->amount_off) {
                        $totalPrice -= $promotionCode->coupon->amount_off / 100;
                    }


                } else {
                    $error_message = 'No promotion code found with that code.';
                }

            } catch (\Exception $e) {
                $error_message = 'Invalid Promotion Code.';
            }
        }

        if ($totalPrice >= 10000) {
            $totalPrice = number_format($totalPrice / 100, 2, '.', ',');
        } else {
            $totalPrice = number_format($totalPrice, 2, '.');
        }


        return '$' . $totalPrice;
    }

    public function subscribePlan(Request $request)
    {

        $user = Auth::user();

        $plan = env('STRIPE_FIRST_PLAN_PRICE');

        // Create the customer on Stripe
        $customer = $this->stripe->customers->create([
            'name' => $request->name,
            'email' => $user->email,
            'source' => $request->token_id,
            'address' => array('state' => $request->state)
        ]);

        // Subscribe the customer to the plan
        $subscription = $this->stripe->subscriptions->create([
            'customer' => $customer->id,
            'items' => [['plan' => $plan]],
            'coupon' => env('STRIPE_COUPON')
        ]);

        $checkout_policy_agreement = $request->checkout_policy_agreement === true ? 1 : 0;

        if ($subscription) {
            Customer::where('id', $user->id)
                ->update([
                    'stripe_customer_id' => $customer->id,
                    'user_type' => 2,
                    'checkout_policy_agreement' => $checkout_policy_agreement
                ]);

            // Redirect to a confirmation page
            return Inertia::location(route('purchase.index'));
        }

        return Redirect::to('/checkout');
    }

    public function purchaseSuccess()
    {
        return Inertia::render('PurchaseSuccess');
    }

//    Marketing Site

    public function marketingCheckout(Request $request)
    {
        $requestedData = $request->all();

        $planId = null;
        $data = null;
        $totalPrice = null;

        $planId = env('STRIPE_FIRST_PLAN_PRICE');


        if ($planId) {

            $promoCodes = [
                'earlybirdfirsttime', // Your default promo code
            ];

// Add the promo code from the request if it exists
            if ($request->input('promotion_code')) {
                $promoCodes[] = $request->input('promotion_code');
            }

            // Check if a request is made to remove a promo code
            if ($request->input('remove_promotion_code')) {
                // Remove the specified promo code from the array
                $promoCodes = array_filter($promoCodes, function ($code) use ($request) {
                    return $code !== $request->input('remove_promotion_code');
                });
            }

            $retrievedPromoCodes = [];
            $error_messages = [];
            $plan = $this->stripe->plans->retrieve($planId);
            $product = $this->stripe->products->retrieve($plan->product);
            $totalPriceCents = $plan->amount;
            $formattedSubTotal = number_format($plan->amount / 100, 2, '.', ',');

            foreach ($promoCodes as $code) {
                try {
                    $promotionCodeDetails = $this->stripe->promotionCodes->all([
                        'code' => $code,
                    ])->data;

                    if (!empty($promotionCodeDetails)) {
                        // Assuming you want the first match since codes should be unique
                        $promoCode = $promotionCodeDetails[0];

                        // Check if the promotion code is valid and has not expired
                        if ($promoCode->coupon->valid) {
                            if ($promoCode->coupon->percent_off) {
                                $totalPriceCents = $totalPriceCents * (1 - ($promoCode->coupon->percent_off / 100));
                            } elseif ($promoCode->coupon->amount_off) {
                                $totalPriceCents -= $promoCode->coupon->amount_off;
                            }
                        }

                        $retrievedPromoCodes[] = $promoCode;
                    } else {
                        $error_messages[$code] = 'No promotion code found with that code.';
                    }
                } catch (\Exception $e) {
                    $error_messages[$code] = 'Error retrieving promotion code: ' . $e->getMessage();
                }
            }

// After all promo codes have been applied, format the total price
// Convert cents to dollars for display
            $totalPriceDollars = $totalPriceCents / 100;

// Apply conditional formatting based on the total price
            $formattedTotalPrice = $totalPriceDollars >= 1000 ?
                number_format($totalPriceDollars, 2, '.', ',') :
                number_format($totalPriceDollars, 2, '.', '');

// Package the data for the view
            $data = array(
                'subTotal' => '$' . $formattedSubTotal,
                'planName' => $product->name,
                'promotionCodes' => $retrievedPromoCodes, // Include the applied promotion codes
                'errorMessages' => $error_messages, // Include any error messages
            );

            $totalPrice = '$' . $formattedTotalPrice;
        }


        return response()->json([
            'data' => $data,
            'totalPrice' => $totalPrice
            ]);

    }

    public function registerAndSubscribe(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            // Field names updated to match the form
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email', // Assuming 'customers' is the table name
            'password' => ['required', 'confirmed', Password::defaults()],
            'policy_agreement' => ['required'], // Make sure the name matches the form's checkbox name
            'stripeToken' => 'required', // Assuming this is the name of the hidden Stripe token field in the form
            'Zip' => 'required|numeric', // Zip code validation
        ]);

        // Register the user
        $policy_agreement = $validatedData['policy_agreement'] == '1'; // Check if the policy agreement checkbox was checked
        $full_name = trim($validatedData['firstName'] . ' ' . $validatedData['lastName']);

        $customer = Customer::create([
            'first_name' => $validatedData['firstName'],
            'last_name' => $validatedData['lastName'],
            'name' => $full_name,
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'policy_agreement' => $policy_agreement,
            // Add other fields as needed
        ]);

        event(new Registered($customer));
//        Auth::login($customer);

        // Proceed with Stripe subscription
        $plan = env('STRIPE_FIRST_PLAN_PRICE');

        try {
            $stripeCustomer = $this->stripe->customers->create([
                'name' => $full_name,
                'email' => $customer->email,
                'source' => $validatedData['stripeToken'],
                'address' => ['postal_code' => $validatedData['Zip']]
            ]);

            $subscription = $this->stripe->subscriptions->create([
                'customer' => $stripeCustomer->id,
                'items' => [['plan' => $plan]],
                 'coupon' => env('STRIPE_COUPON')
            ]);

            if ($subscription) {
                $customer->update([
                    'stripe_customer_id' => $stripeCustomer->id,
                    'user_type' => 2
                ]);
            }
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle exception
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }


        return response()->json(['success' => true], 200);
    }


}
