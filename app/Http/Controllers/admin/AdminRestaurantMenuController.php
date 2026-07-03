<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminRestaurantMenuController extends Controller
{
    public function index()
    {
        $menus = RestaurantMenu::latest()->paginate(15);

        return view('admin.restaurant-menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.restaurant-menus.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['nullable', 'string', 'max:100'],
            'is_available' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
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
        return view('admin.restaurant-menus.edit', compact('restaurantMenu'));
    }

    public function update(Request $request, RestaurantMenu $restaurantMenu)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['nullable', 'string', 'max:100'],
            'is_available' => ['sometimes', 'boolean'],
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
