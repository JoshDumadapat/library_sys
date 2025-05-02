<?php
namespace App\Http\Controllers;

use App\Models\User;  // Assuming your employees are users with a specific role
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // Show the employee list
    public function index()
    {
        // Fetch all employees who are not members (or adjust the query as needed)
        $employees = User::where('role', '!=', 'member')->get();  // Correct: Fetching a collection of employees

        // Pass employees data to the view
        return view('admin.employees', compact('employees'));  // Change 'employee' to 'employees' to reflect a collection
    }

    // Show the edit employee form
    public function edit($id)
    {
        $employee = User::findOrFail($id);

        // Ensure the employee is not a member (only employees should be editable)
        if (strtolower($employee->role) == 'member') {
            abort(403, 'Unauthorized action.');
        }

        // Pass the employee data to the view
        return view('admin.employees.edit', compact('employee'));
    }

    // Update the employee details
    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        // Ensure the employee is not a member and prevent updating the role to certain values if needed
        if (strtolower($employee->role) == 'member') {
            abort(403, 'Unauthorized action.');
        }

        // Validate the form input
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_num' => 'required|string|min:11|max:11',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'role' => 'required|string|max:255', // Consider validation for allowed roles (admin, etc.)
            'email' => 'required|email|max:255',
        ]);

        // Update employee data
        $employee->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'contact_num' => $request->contact_num,
            'street' => $request->street,
            'city' => $request->city,
            'region' => $request->region,
            'role' => $request->role, // Make sure role changes are allowed
            'email' => $request->email,
        ]);

        return redirect()->route('admin.employees')->with('success', 'Employee updated successfully');
    }

   
public function store(Request $request)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'contact_num' => 'required|string|max:15',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:8',
        'street' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'region' => 'required|string|max:255',
        'role' => 'required|in:admin,librarian',
    ]);

    // Create User
    $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'contact_num' => $request->contact_num,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
    ]);

    // Create Address
    Address::create([
        'user_id' => $user->id,
        'street' => $request->street,
        'city' => $request->city,
        'region' => $request->region,
    ]);

    return redirect()->route('employee.index')->with('success', 'Employee added successfully.');
}

}



