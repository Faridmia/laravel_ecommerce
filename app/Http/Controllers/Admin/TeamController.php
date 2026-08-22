<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    /**
     * List all team members.
     */
    public function list()
    {
        $data['getRecord'] = Team::orderBy('id', 'desc')->get();
        $data['header_title'] = "Team Members";
        return view('admin.team.list', $data);
    }

    /**
     * Show add form.
     */
    public function add()
    {
        $data['header_title'] = "Add Team Member";
        return view('admin.team.add', $data);
    }

    /**
     * Store new team member.
     */
    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'facebook_link' => 'nullable|max:500',
            'twitter_link' => 'nullable|max:500',
            'instagram_link' => 'nullable|max:500',
        ]);

        $team = new Team();
        $team->name = trim($request->name);
        $team->designation = trim($request->designation);
        $team->facebook_link = trim($request->facebook_link);
        $team->twitter_link = trim($request->twitter_link);
        $team->instagram_link = trim($request->instagram_link);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                if (!file_exists(public_path('upload/team'))) {
                    mkdir(public_path('upload/team'), 0777, true);
                }
                $filename = 'team_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/team/'), $filename);
                $team->image = $filename;
            }
        }

        $team->save();
        return redirect()->route('admin.team.list')->with('success', 'Team Member added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $data['member'] = Team::findOrFail($id);
        $data['header_title'] = "Edit Team Member";
        return view('admin.team.edit', $data);
    }

    /**
     * Update team member.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'facebook_link' => 'nullable|max:500',
            'twitter_link' => 'nullable|max:500',
            'instagram_link' => 'nullable|max:500',
        ]);

        $team = Team::findOrFail($id);
        $team->name = trim($request->name);
        $team->designation = trim($request->designation);
        $team->facebook_link = trim($request->facebook_link);
        $team->twitter_link = trim($request->twitter_link);
        $team->instagram_link = trim($request->instagram_link);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                if (!empty($team->image) && file_exists(public_path('upload/team/' . $team->image))) {
                    @unlink(public_path('upload/team/' . $team->image));
                }
                if (!file_exists(public_path('upload/team'))) {
                    mkdir(public_path('upload/team'), 0777, true);
                }
                $filename = 'team_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/team/'), $filename);
                $team->image = $filename;
            }
        }

        $team->save();
        return redirect()->route('admin.team.list')->with('success', 'Team Member updated successfully.');
    }

    /**
     * Delete team member.
     */
    public function delete($id)
    {
        $team = Team::findOrFail($id);
        if (!empty($team->image) && file_exists(public_path('upload/team/' . $team->image))) {
            @unlink(public_path('upload/team/' . $team->image));
        }
        $team->delete();
        return redirect()->route('admin.team.list')->with('success', 'Team Member deleted successfully.');
    }
}
