<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomExtensionPackage;
use Illuminate\Http\Request;

class AdminCustomExtendController extends Controller
{
    public function index()
    {
        $packages = CustomExtensionPackage::orderBy('sort_order', 'asc')->get();
        return view('admin.custom-extend.index', compact('packages'));
    }

    public function edit($id)
    {
        $package = CustomExtensionPackage::findOrFail($id);
        return view('admin.custom-extend.edit', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $package = CustomExtensionPackage::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'days' => 'required|integer|min:1',
            'price_coinkey' => 'required|numeric|min:0',
            'price_vnd' => 'required|numeric|min:2000',
        ]);

        $package->update([
            'name' => $request->name,
            'days' => $request->days,
            'price_coinkey' => $request->price_coinkey,
            'price_vnd' => $request->price_vnd,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.custom-extend.index')
                         ->with('success', 'Cập nhật thành công!');
    }
}