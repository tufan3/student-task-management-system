<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::all();
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'scheduled_at' => 'nullable|date',
        ]);



        $announcement = new Announcement();
        $announcement->title = $request->title;
        $announcement->description = $request->description;
        if ($request->scheduled_at != null) {
            $announcement->scheduled_at = $request->scheduled_at;
        }else{
            $announcement->scheduled_at = now();
        }


        if ($request->hasFile('image')) {
            // $originalPath = $request->file('image')->store('announcements/originals', 'public');

            // $image = Image::make(storage_path("app/public/{$originalPath}"))->encode('webp', 90);
            // $webpPath = 'announcements/webp/' . uniqid() . '.webp';
            // Storage::disk('public')->put($webpPath, $image);
            // $announcement->image = $webpPath;

            $imagePath = $request->file('image')->store('announcements/images', 'public');
            $announcement->image = $imagePath;

        }


        // if ($request->hasFile('image')) {

        //     $manager = new ImageManager(new Driver());

        //     $image = $request->file('image');
        //     $thumbnail_name = $announcement->id . '.' . $image->getClientOriginalExtension();

        //     $img = $manager->read($image);
        //     $img->resize(600, 600)->save(storage_path('app/public/files/announcements/' . $thumbnail_name));
        //     $announcement->image = $thumbnail_name;
        //     // $announcement->save();
        // }

        $announcement->save();

        return redirect()->route('announcements.index')->with('success', 'Announcement created successfully');
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'scheduled_at' => 'nullable|date',
        ]);

        $announcement->title = $request->title;
        $announcement->description = $request->description;

        if ($request->scheduled_at != null) {
            $announcement->scheduled_at = $request->scheduled_at;
        }else{
            $announcement->scheduled_at = now();
        }

        if ($request->hasFile('image')) {
            if ($announcement->image && \Storage::disk('public')->exists($announcement->image)) {
                \Storage::disk('public')->delete($announcement->image);
            }

            $imagePath = $request->file('image')->store('announcements/images', 'public');
            $announcement->image = $imagePath;
        }

        // \Log::info('Current Time: ' . now());
        // \Log::info('Scheduled At: ' . $announcement->scheduled_at ?? 'No announcement');


        $announcement->save();

        return redirect()->route('announcements.index')->with('success', 'Announcement updated successfully');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->image && \Storage::disk('public')->exists($announcement->image)) {
            \Storage::disk('public')->delete($announcement->image);
        }
        $announcement->delete();
        return redirect()->route('announcements.index')->with('success', 'Announcement deleted successfully');
    }


}
