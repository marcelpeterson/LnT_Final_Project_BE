<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function viewCart()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('item')->get();
        return view('Invoice/cart', compact('cartItems'));
    }

    public function addToCart(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'quantity' => 'required|numeric|min:1'
        ]);

        $cart = Cart::where('user_id', Auth::id())
            ->where('item_id', $id)
            ->first();

        if ($cart) {
            $newQuantity = $cart->quantity + $request->quantity;

            if ($newQuantity > $item->quantity) {
                return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
            }

            $cart->quantity = $newQuantity;
            $cart->save();
        } else {
            if ($request->quantity > $item->quantity) {
                return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
            }

            Cart::create([
                'user_id' => Auth::id(),
                'item_id' => $id,
                'quantity' => $request->quantity
            ]);
        }

        $item->quantity -= $request->quantity;
        $item->save();

        return redirect()->back()->with('success', 'Item added to cart successfully!');
    }
    
    public function updateCart(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $item = Item::findOrFail($cart->item_id);

        $request->validate([
            'quantity' => 'required|numeric|min:1'
        ]);

        $difference = $request->quantity - $cart->quantity;
        if ($difference > 0 && $difference > $item->quantity) {
            return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
        }

        $item->quantity -= $difference;
        $item->save();

        $cart->quantity = $request->quantity;
        $cart->save();
        
        return redirect()->back()->with('success', 'Cart updated successfully!');
    }
    
    public function removeFromCart($id)
    {
        $cart = Cart::findOrFail($id);

        $item = Item::findOrFail($cart->item_id);
        $item->quantity += $cart->quantity;
        $item->save();
    
        $cart->delete();
        
        return redirect()->back()->with('success', 'Item removed from cart successfully!');
    }
    
    public function checkout(Request $request)
    {
        $request->validate([
            'address' => 'required|string|min:10|max:100',
            'postcode' => 'required|numeric|digits:5'
        ], [
            'address.required' => 'Address is required',
            'address.min' => 'Address must be at least 10 characters',
            'address.max' => 'Address must not exceed 100 characters',
            'postcode.required' => 'Postcode is required',
            'postcode.numeric' => 'Postcode must be a number',
            'postcode.digits' => 'Postcode must be 5 digits'
        ]);
        
        $cartItems = Cart::where('user_id', Auth::id())->with('item')->get();
        
        if ($cartItems->count() == 0) {
            return redirect()->back()->with('error', 'Your cart is empty');
        }
        
        $totalAmount = $cartItems->sum(function($cartItem) {
            return $cartItem->item->price * $cartItem->quantity;
        });
        
        $invoice = Invoice::create([
            'number' => 'INV-' . Str::random(8),
            'user_id' => Auth::id(),
            'address' => $request->address,
            'postcode' => $request->postcode,
            'total_amount' => $totalAmount
        ]);
        
        foreach ($cartItems as $cartItem) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_id' => $cartItem->item_id,
                'name' => $cartItem->item->name,
                'price' => $cartItem->item->price,
                'quantity' => $cartItem->quantity
            ]);
        }
        
        Cart::where('user_id', Auth::id())->delete();
        
        return redirect()->route('invoice.show', $invoice->id)->with('success', 'Order placed successfully!');
    }
    
    public function showInvoice($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        return view('Invoice/show', compact('invoice'));
    }
    
    public function getInvoices()
    {
        $invoices = Invoice::where('user_id', Auth::id())->get();
        return view('Invoice/index  ', compact('invoices'));
    }
}
