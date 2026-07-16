<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminRestaurantMenuController extends Controller
{
    public function index(Request $request)
    {
        $query = RestaurantMenu::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $menus = $query->latest()->paginate(15);

        $categories = RestaurantMenu::CATEGORIES;

        return view('admin.restaurant-menus.index', compact('menus', 'categories'));
    }

    public function create()
    {
        $categories = RestaurantMenu::CATEGORIES;
        return view('admin.restaurant-menus.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'in:' . implode(',', array_keys(RestaurantMenu::CATEGORIES))],
            'is_available' => ['sometimes', 'boolean'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $data['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('restaurant-menus', 'public');
        }

        RestaurantMenu::create($data);

        return redirect()->route('admin.restaurant-menus.index')
            ->with('status', 'Menu restoran berhasil disimpan.');
    }

    public function edit(RestaurantMenu $restaurantMenu)
    {
        $categories = RestaurantMenu::CATEGORIES;
        return view('admin.restaurant-menus.edit', compact('restaurantMenu', 'categories'));
    }

    public function update(Request $request, RestaurantMenu $restaurantMenu)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'in:' . implode(',', array_keys(RestaurantMenu::CATEGORIES))],
            'is_available' => ['sometimes', 'boolean'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            if ($restaurantMenu->image) {
                Storage::disk('public')->delete($restaurantMenu->image);
            }
            $data['image'] = $request->file('image')->store('restaurant-menus', 'public');
        }

        $restaurantMenu->update($data);

        return redirect()->route('admin.restaurant-menus.index')
            ->with('status', 'Menu restoran berhasil diperbarui.');
    }

    public function destroy(RestaurantMenu $restaurantMenu)
    {
        if ($restaurantMenu->image) {
            Storage::disk('public')->delete($restaurantMenu->image);
        }

        $restaurantMenu->delete();

        return redirect()->route('admin.restaurant-menus.index')
            ->with('status', 'Menu restoran berhasil dihapus.');
    }
}
