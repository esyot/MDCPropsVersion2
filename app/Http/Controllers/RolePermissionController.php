<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManagedCategory;
class RolePermissionController extends Controller
{
    public function managedCategoriesUpdate(Request $request, $category_id)
    {

        if ($request->isUncheckedAll == "false") {

            $request->validate([
                'users' => 'required|array',
                'users.*' => 'array',
                'users.*.*' => 'required',
            ]);


            foreach ($request->users as $categoryId => $userIds) {

                foreach ($userIds as $userId) {

                    $exists = ManagedCategory::where('user_id', $userId)
                        ->where('category_id', $categoryId)
                        ->exists();


                    if (!$exists) {
                        ManagedCategory::create([
                            'user_id' => $userId,
                            'category_id' => $categoryId,
                        ]);
                    }
                }

                $currentUserIds = ManagedCategory::where('category_id', $categoryId)
                    ->pluck('user_id')
                    ->toArray();


                $usersToRemove = array_diff($currentUserIds, $userIds);

                if (!empty($usersToRemove)) {
                    ManagedCategory::where('category_id', $categoryId)
                        ->whereIn('user_id', $usersToRemove)
                        ->delete();
                }

            }
        } else if ($request->isUncheckedAll == "true") {
            ManagedCategory::where('category_id', $category_id)->delete();

        } else {


            return redirect()->back();

        }

        return redirect()->back()->with('success', 'Managed Categories updated successfully!');
    }
}
