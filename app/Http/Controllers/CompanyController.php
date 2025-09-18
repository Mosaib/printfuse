<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\ActiveCompany;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\ActiveCompanyResource;


class CompanyController extends Controller
{

    public function index()
    {
        $companies = Auth::user()->companies()->get();
        if ($companies->isEmpty()) {
            return response()->json(['status' => 'success', 'message' => 'No companies found']);
        }
        $companiesResource = CompanyResource::collection($companies);
        return response()->json([
            'status' => 'success',
            'message' => 'Companies fetched successfully',
            'data' => $companiesResource,]);
    }


    //add company
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
        ]);

        $company = Auth::user()->companies()->create($data);

        $companiesResource = new CompanyResource($company);
        return response()->json([
            'status' => 'success',
            'message' => 'Company added successfully',
            'data' => $companiesResource
        ]);
    }

    //update
    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|int|exists:companies,id',
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|nullable|string|max:255',
            'industry' => 'sometimes|nullable|string|max:255',
        ]);

        $company = Company::findOrFail($data['id']);

        if ($company->user_id !== Auth::id()) {
            return response()->json(['message' => 'You are not associated with this company.']);
        }

        $company->update($data);
        $companiesResource = new CompanyResource($company);
        return response()->json([
            'status' => 'success',
            'message' => 'Company updated successfully',
            'data' => $companiesResource
        ]);
    }


    //delete
    public function destroy(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|int|exists:companies,id',
        ]);

        $company = Company::where('id', $data['id'])->where('user_id', Auth::id())->first();
        if (!$company) {
            return response()->json(['message' => 'You are not associated with this company or company is deleted.']);
        }

        $company->delete();
        $companiesResource = new CompanyResource($company);
        return response()->json([
            'status' => 'success',
            'message' => 'Company deleted successfully',
            'data' => $companiesResource
        ]);
    }


    public function switchActiveCompany(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'required|numeric|exists:companies,id',
        ]);

        $company = Company::where('id', $data['company_id'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$company) {
            return response()->json(['message' => 'You are not associated with this company or company is deleted.']);
        }

        $active = ActiveCompany::updateOrCreate(
            ['user_id' => Auth::id()],
            ['company_id' => $company->id]
        );
        $activecompanyResource = new ActiveCompanyResource($active);
        return response()->json([
            'status' => 'success',
            'message' => 'Active company switched',
            'data' => $activecompanyResource
        ]);
    }

}
