<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    function getItemPage()
    {
        $items = Item::all();
        return view('home', compact('items'));
    }

    function getCreatePage()
    {
        $categories = Category::all();
        return view('Item/create', compact('categories'));
    }

    function createItem(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|min:5|max:80',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'photo' => 'required',
        ], [
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Category does not exist',
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 5 characters',
            'name.max' => 'Name must not exceed 80 characters',
            'price.required' => 'Price is required',
            'quantity.required' => 'Quantity is required',
            'photo.required' => 'Photo is required',
        ]);
        
        $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->storeAs('item_images', $filename, 'public');

        $item = Item::create([
            'category_id' => $request->input('category_id'),
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'quantity' => $request->input('quantity'),
            'photo' => $filename,
        ]);

        return redirect(route('home'))->with('success', 'Item created successfully');
    }

    function deleteItem($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        return redirect()->back()->with('success', 'Item deleted successfully');
    }

    function getEditPage($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all();
        return view('Item/edit', compact('item', 'categories'));
    }
    
    function editItem(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|min:5|max:80',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'photo' => 'nullable|image|max:2048',
        ], [
            'category_id.required' => 'Category is required',
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 5 characters',
            'name.max' => 'Name must not exceed 80 characters',
            'price.required' => 'Price is required',
            'quantity.required' => 'Quantity is required',
            'photo.image' => 'Photo must be an image file',
        ]);

        $item = Item::findOrFail($id);

        if ($request->hasFile('photo')) {
            $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->storeAs('item_images', $filename, 'public');
            $item->photo = $filename;
        }

        $item->category_id = $request->input('category_id');
        $item->name = $request->input('name');
        $item->price = $request->input('price');
        $item->quantity = $request->input('quantity');
        $item->save();

        return redirect(route('home'))->with('success', 'Item updated successfully');
    }
}
