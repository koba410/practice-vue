<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $items = Item::select(['id', 'name', 'price', 'is_selling'])->get();

        return Inertia::render('Items/Index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Items/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreItemRequest $request
     * @return RedirectResponse
     */
    public function store(StoreItemRequest $request): RedirectResponse
    {
        Item::create([
            'name' => $request->name,
            'memo' => $request->memo,
            'price' => $request->price,
        ]);

        return to_route('items.index')->with([
            'message' => '商品を登録しました。',
            'status' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     * 
     * @param Item $item
     * @return Response
     */
    public function show(Item $item)
    {
        // dd($item);
        return Inertia::render('Items/Show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param Item $item
     * @return Response
     */
    public function edit(Item $item)
    {
        return Inertia::render('Items/Edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param UpdateItemRequest $request
     * @param Item $item
     * @return RedirectResponse
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $item->update([
            'name' => $request->name,
            'memo' => $request->memo,
            'price' => $request->price,
            'is_selling' => $request->is_selling,
        ]);

        return to_route('items.index')->with([
            'message' => '商品を編集しました。',
            'status' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Item $item
     * @return RedirectResponse
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return to_route('items.index')->with([
            'message' => '商品を削除しました。',
            'status' => 'danger',
        ]);
    }
}
