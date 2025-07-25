<?php

namespace App\Http\Controllers;

use App\Models\TimingTag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // uniqueルールで使用するため追加

use function Ramsey\Uuid\v1;

class TimingTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timingTags = TimingTag::orderBy('timing_tag_id','asc')->get();
        // dd($timingTags);
        return view('timingtags.index',compact('timingTags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('timingtags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'timing_name' => 'required|string|max:255|unique:timing_tags,timing_name',
            'base_time' => 'nullable|date_format:H:i', // HH:MM形式の時間を許可
        ]);

        TimingTag::create([
            'timing_name' => $request->timing_name,
            'base_time' => $request->base_time,
        ]);

        return redirect()->route('timingtags.index')
                        ->with('status','新しい服用タイミングが登録されました');
    }

    /**
     * Display the specified resource.
     */
    public function show(TimingTag $timingtag)
    {
        // dd($timingtag);
        return view('timingtags.show',compact('timingtag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TimingTag $timingtag)
    {
        // dd($timingtag);
        return view('timingtags.edit',compact('timingtag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TimingTag $timingtag)
    {
        $request->validate([
            'timing_name' => [
                'required',
                'string',
                'max:255',
                // 更新時は自分自身の名前を除外してuniqueチェックを行う
                Rule::unique('timing_tags')->ignore($timingtag->timing_tag_id, 'timing_tag_id'),
            ],
            'base_time' => 'nullable|date_format:H:i', // HH:MM形式の時間を許可
        ]);

        $timingtag->update([
            'timing_name' => $request->timing_name,
            'base_time' => $request->base_time,
        ]);

        return redirect()->route('timingtags.show', $timingtag)
                        ->with('status','服用タイミングが更新されました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
