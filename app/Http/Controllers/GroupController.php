<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Member;
use DB;

class GroupController extends Controller
{
    //
    public function index($club){
      $club = implode(' ', explode('-', $club));
      $groups = Group::selectRaw('groups.name, groups.id, COUNT(members.id) AS number_members, clubs.id AS club_id')
      ->join('members','groups.id','members.groups')->join('clubs','groups.club','clubs.id')
      ->where('clubs.name', $club)
      ->groupby('groups.name', 'groups.id', 'clubs.id')->get();
      return view('club.groups', compact('groups'));
    }

    public function join(Request $request){
      $group = $request->group;      $user = $request->user;      $club = $request->club;
      Member::create(['groups' => $group, 'user' => $user, 'club' => $club,]);
      $id = Group::where('id',$group)->where('club',$club)->first()->id;
      return response()->json(['status' => true, 'id' => $id]);
    }

    public function leave(Request $request){
      $group = $request->group;      $user = $request->user;      $club = $request->club;
      Member::create(['groups' => $group, 'user' => $user, 'club' => $club,]);
      $id = Group::where('id',$group)->where('club',$club)->first()->id;
      return response()->json(['status' => true, 'id' => $id]);
    }

    public function create(Request $request){
      $name = $request->name;      $user = $request->user;      $club = $request->club;
      $group = Group::create(['name' => $name, 'creator' => $user, 'club' => $club,]);
      if($group){
        $id = Group::where('name',$name)->where('creator',$user)->where('club',$club)->first()->id;
        Member::create(['groups' => $id, 'user' => $user, 'club' => $club,]);
        return response()->json(['status' => true, 'id' => $id]);
      }
      else{return response()->json(['status' => false]);}
    }

    public function view($club,$group){
      $group = implode(' ', explode('-', $group));
      $club = implode(' ', explode('-', $club));
      //group
      $group = Group::selectRaw('groups.name, groups.id, groups.creator, groups.club, clubs.name AS club, users.name AS creator, COUNT(members.id) AS number_members')
      ->join('users','groups.creator','users.id')->join('members','groups.id','members.groups')
      ->join('clubs','groups.club','clubs.id')->where('groups.name',$group)
      ->groupby('groups.name', 'groups.id', 'groups.creator', 'groups.club', 'users.name', 'clubs.name')->first();
      //group members
      $members = Member::selectRaw('users.name')->join('users','members.user','users.id')
      ->where('members.groups',$group->id)->get();
      return view('club.group', compact('group', 'members'));
    }
}
