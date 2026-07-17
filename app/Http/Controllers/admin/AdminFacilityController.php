<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminFacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::latest()->paginate(15);

        return view('admin.facilities.index', compact('facilities'));
    }

    public function create()
    {
        return view('admin.facilities.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'icon'          => ['nullable', 'string', 'max:100'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status'        => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['status'] = $request->boolean('status');
        $data['display_order'] = (int) $request->input('display_order', 0);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('facilities', 'public');
        }

        Facility::create($data);

        return redirect()->route('admin.facilities.index')
            ->with('status', 'Fasilitas berhasil disimpan.');
    }

    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'icon'          => ['nullable', 'string', 'max:100'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status'        => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['status'] = $request->boolean('status');
        $data['display_order'] = (int) $request->input('display_order', 0);

        if ($request->hasFile('image')) {
            if ($facility->image) {
                Storage::disk('public')->delete($facility->image);
            }
            $data['image'] = $request->file('image')->store('facilities', 'public');
        }

        $facility->update($data);

        return redirect()->route('admin.facilities.index')
            ->with('status', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Facility $facility)
    {
        if ($facility->image) {
            Storage::disk('public')->delete($facility->image);
        }

        $facility->delete();

        return redirect()->route('admin.facilities.index')
            ->with('status', 'Fasilitas berhasil dihapus.');
    }
}
