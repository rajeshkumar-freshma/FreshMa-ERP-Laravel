<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DenominationType;
use App\Models\IncomeExpenseType;
use App\Models\ItemType;
use App\Models\MachineData;
use App\Models\Partner;
use App\Models\PartnershipType;
use App\Models\PaymentType;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\TaxRate;
use App\Models\TransportType;
use App\Models\Unit;
use App\Models\Vendor;
use Illuminate\Http\Request;

class MasterStatusController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'entity' => 'required|string',
            'id' => 'required|integer',
            'status_value' => 'required|in:0,1',
        ]);

        $entityMap = [
            'category' => Category::class,
            'denomination_type' => DenominationType::class,
            'income_expense_type' => IncomeExpenseType::class,
            'item_type' => ItemType::class,
            'machine_detail' => MachineData::class,
            'partner' => Partner::class,
            'partnership_type' => PartnershipType::class,
            'payment_type' => PaymentType::class,
            'store' => Store::class,
            'supplier' => Supplier::class,
            'tax_rate' => TaxRate::class,
            'transport_type' => TransportType::class,
            'unit' => Unit::class,
            'vendor' => Vendor::class,
        ];

        $modelClass = $entityMap[$validated['entity']] ?? null;

        if ($modelClass === null) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid entity',
            ]);
        }

        $modelClass::where('id', $validated['id'])->update([
            'status' => (int) $validated['status_value'],
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Status update Successfully',
        ]);
    }
}
