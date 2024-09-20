<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $query = Sale::query();

            $query->with(['firm' => function ($q) {
                $q->select('id', 'firm_name as text');
            }, 'billType' => function ($q) {
                $q->select('id', 'text');
            }, 'party' => function ($q) {
                $q->select('id', 'party_name as text');
            },]);

            if ($request->has('search')) {
                $query->whereHas('party', function ($q) use ($request) {
                    $q->where('party_name', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->has('date')) {
                $query->whereMonth('sale_date', $request->date['month'])
                    ->whereYear('sale_date', $request->date['year']);
            }

            if ($request->has('bill_type_id')) {
                $query->whereHas('billType', function ($q) use ($request) {
                    $q->where('id', $request->bill_type_id);
                });
            }

            $query->latest();

            $data = $query->paginate(10);

            // Format the data to include only the necessary columns
            $formattedData = $data->getCollection()->map(function ($sale) {
                return array_merge(
                    $sale->toArray(),
                    [
                        'firm' => $sale->firm ? $sale->firm->text : null,
                        'bill_type' => $sale->billType ? $sale->billType->text : null,
                        'party' => $sale->party ? $sale->party->text : null,
                        'location' => $sale->location(),
                        'total' => '₹' . number_format($sale->total, 2),
                        'cgst' => '₹' . number_format($sale->cgst, 2),
                        'sgst' => '₹' . number_format($sale->sgst, 2),
                        'igst' => '₹' . number_format($sale->igst, 2),
                        'grand_total' => '₹' . number_format($sale->grand_total, 2),
                    ]
                );
            });

            // Create a new pagination data instance with the formatted data
            $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
                $formattedData,
                $data->total(),
                $data->perPage(),
                $data->currentPage(),
                ['path' => $request->url(), 'query' => $request->query()]
            );

            // Return success response
            return response()->json($paginatedData, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSaleRequest $request)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the Sale record
            $sale = Sale::create($request->only([
                'firm_id',
                'sale_date',
                'bill_type_id',
                'bill_no',
                'days',
                'due_date',
                'party_id',
                'location_id',
                'ex_rate',
                'currency_id',
                'total',
                'cgst',
                'sgst',
                'igst',
                'grand_total',
            ]));

            // Create the SaleDetail records
            foreach ($request->input('sale_details') as $detail) {
                $sale->saleDetails()->create($detail);
            }

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Sale created successfully!', 'sale' => $sale], 201);
        } catch (\Exception $e) {
            // Rollback the transaction if something failed
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while saving the sale.', $e], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $query = Sale::query();

            $query->with(['firm' => function ($q) {
                $q->select('id', 'firm_name');
            }, 'billType' => function ($q) {
                $q->select('id', 'alias', 'text');
            }, 'party' => function ($q) {
                $q->select('id', 'party_name');
            }, 'currency' => function ($q) {
                $q->select('id', 'text');
            }, 'saleDetails' => function ($q) {
                $q->select('id', 'sale_id', 'product_id', 'description', 'quantity', 'rate', 'dollar', 'amount');
            }, 'saleDetails.product' => function ($q) {
                $q->select('id', 'product_name');
            },]);

            $sale = $query->find($id);

            if ($sale) {

                if ($sale->firm) {
                    $sale->firm_id = $sale->firm;
                }
                if ($sale->billType) {
                    $sale->bill_type_id = $sale->billType;
                }
                if ($sale->party) {
                    $sale->party_id = $sale->party;
                }
                if ($sale->currency) {
                    $sale->currency_id = $sale->currency;
                }

                $sale->location_id = $sale->location(true);

                // Modify the saleDetails array by replacing product with product_id
                $sale->saleDetails = $sale->saleDetails->map(function ($saleDetail) {
                    $saleDetailArray = $saleDetail->toArray();

                    // Set the product_id with id and product_name
                    $saleDetailArray['product_id'] = [
                        'id' => $saleDetailArray['product']['id'],
                        'product_name' => $saleDetailArray['product']['product_name']
                    ];

                    // Add product_name at the top level
                    $saleDetailArray['product_name'] = $saleDetailArray['product']['product_name'];

                    // Remove the original product array
                    unset($saleDetailArray['product']);

                    return $saleDetailArray;
                });

                // Unset the original sale_details key
                unset($sale->sale_details);

                // Rename saleDetails to sale_details
                $sale->sale_details = $sale->saleDetails;

                // Unset the original saleDetails key if you no longer need it
                unset($sale->saleDetails);

                // Return success response
                return response()->json(['message' => 'Sale fetched successfully!', 'data' => $sale], 201);
            } else {
                // Return not found response
                return response()->json(['error' => 'Sale not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSaleRequest $request, $id)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Find the Sale record
            $sale = Sale::findOrFail($id);

            // Update the Sale record
            $sale->update($request->only([
                'firm_id',
                'sale_date',
                'bill_type_id',
                'bill_no',
                'days',
                'due_date',
                'party_id',
                'location_id',
                'ex_rate',
                'currency_id',
                'total',
                'cgst',
                'sgst',
                'igst',
                'grand_total',
            ]));

            // Get the existing SaleDetails IDs
            $existingDetailIds = $sale->saleDetails()->pluck('id')->toArray();

            $incomingDetailIds = collect($request->input('sale_details'))->pluck('id')->filter()->toArray();

            // Find and delete SaleDetails that are not in the incoming data
            $sale->saleDetails()->whereNotIn('id', $incomingDetailIds)->delete();

            // Loop through and update or create SaleDetail records
            foreach ($request->input('sale_details') as $detail) {
                unset($detail['product_name']);
                if (isset($detail['id']) && in_array($detail['id'], $existingDetailIds)) {
                    // Update existing SaleDetail
                    SaleDetail::where('id', $detail['id'])->update($detail);
                } else {
                    // Create new SaleDetail
                    $sale->saleDetails()->create($detail);
                }
            }

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Sale updated successfully!', 'sale' => $sale], 201);
        } catch (\Exception $e) {
            // Rollback the transaction if something failed
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while updating the sale.', $e], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $sale = Sale::find($id);

            if ($sale) {
                $sale->delete();

                // Return success response
                return response()->json(['message' => 'Sale deleted successfully!'], 200);
            } else {
                // Return not found response
                return response()->json(['error' => 'Sale not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function createSaleInvoiceId(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'prefix' => 'required|string|max:2',
            'date' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {

            $prefix = $request->input('prefix');
            $date = $request->input('date');

            $invoiceId = Helper::generateSaleBillNo($prefix, $date);

            return response()->json(['invoiceId' => $invoiceId], 201);
        } catch (\InvalidArgumentException $e) {
            // Handle the exception by returning an error response
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function generateInvoicePDF(Request $request, $id = null)
    {
        try {

            $query = Sale::query();
            $query->with(['party' => function ($q) {
                $q->select('id', 'party_name as bill_name', 'address', 'gst_tin as gst_no');
            }, 'saleDetails' => function ($q) {
                $q->select('id', 'sale_id', 'product_id', 'description', 'quantity', 'rate', 'dollar', 'amount');
            }, 'saleDetails.product' => function ($q) {
                $q->select('id', 'product_name', 'hsn_sac');
            }, 'billType' => function ($q) {
                $q->select('id', 'alias', 'text');
            }, 'currency' => function ($q) {
                $q->select('id', 'text');
            }]);
            $sale = $query->find($request->id ?? $id);

            if ($sale) {
                $sale->saleDetails = $sale->saleDetails->map(function ($saleDetail) {
                    $saleDetailArray = $saleDetail->toArray();
                    $saleDetailArray['product_name'] = $saleDetailArray['product']['product_name'];
                    $saleDetailArray['hsn_code'] = $saleDetailArray['product']['hsn_sac'];
                    unset($saleDetailArray['product']);
                    return $saleDetailArray;
                });
                unset($sale->sale_details);
                $sale->sale_details = $sale->saleDetails;
                unset($sale->saleDetails);

                $invoiceData = [
                    'invoice_name' => $sale->billType->text,
                    'invoice_no' => $sale->bill_no,
                    'date' => now()->format('d-m-Y'),
                    'due_date' => Carbon::parse($sale->due_date)->format('d-m-Y'),
                    'company' => 'PRIMENTO TECHNOLOGIES PVT LTD',
                    'address_1' => '4047, Silver Business Point, VIP Circle,',
                    'address_2' => 'Utran, Surat, Gujarat - 394101',
                    'contact_no' => '+91 70986 10111',
                    'gst_no' => '24AAOCP2783B1ZK',
                    'pan_no' => 'AAOCP2783B',
                    'lut_no' => '24AAOCP2783B1ZK',
                    'bill_to' => [
                        'name' => $sale->party->bill_name,
                        'address' => $sale->party->address,
                        'gst_no' => $sale->party->gst_no,
                    ],
                    'items' => $sale->sale_details,
                    'currency' => $sale->currency->text ?? '',
                    'total_amount' => $sale->total,
                    'total_amount_in_words' => ucwords(Helper::amountToWords($sale->total)),
                    'cgst' => $sale->cgst,
                    'sgst' => $sale->sgst,
                    'igst' => $sale->igst,
                    'invoice_total' => $sale->billType->alias == 'EX' ? $sale->sale_details->sum('dollar') : $sale->total,
                    'bank_details' => [
                        'bank' => 'AXIS BANK',
                        'branch' => 'AMROLI BRANCH',
                        'account_name' => 'PRIMENTO TECHNOLOGIES PRIVATE LIMITED',
                        'account_number' => '923020072105748',
                        'ifsc_code' => 'UTIB0003118'
                    ]
                ];

                $invoice_type = 'invoices.' . $sale->billType->alias ?? '';

                $pdf = PDF::loadView($invoice_type, compact('invoiceData'));
                $pdf->setPaper('A4', 'portrait');
                // $pdf->setOption('dpi', 100);
                if ($request->method() == 'GET') {
                    return $pdf->stream();
                } else {
                    return $pdf->download('invoice.pdf');
                }
            } else {
                // Return not found response
                return response()->json(['error' => 'Sale not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.', $e], 500);
        }
    }

    public function generateMultipleInvoicePDF(Request $request)
    {
        try {

            $query = Sale::query();
            $query->with(['party' => function ($q) {
                $q->select('id', 'party_name as bill_name', 'address', 'gst_tin as gst_no');
            }, 'saleDetails' => function ($q) {
                $q->select('id', 'sale_id', 'product_id', 'description', 'quantity', 'rate', 'dollar', 'amount');
            }, 'saleDetails.product' => function ($q) {
                $q->select('id', 'product_name', 'hsn_sac');
            }, 'billType' => function ($q) {
                $q->select('id', 'alias', 'text');
            }, 'currency' => function ($q) {
                $q->select('id', 'text');
            }]);

            $sales = $query->whereIn('id', $request->ids)->get();

            if ($sales) {

                $directoryPath = storage_path('app/public/invoices');

                if (!is_dir($directoryPath)) {
                    mkdir($directoryPath, 0755, true);
                }

                if (File::exists(public_path('/sample.zip'))) {
                    File::delete(public_path('/sample.zip'));
                }

                $zip = new ZipArchive;
                $zipFileName = 'sample.zip';

                if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {

                    foreach ($sales as $index => $sale) {

                        $sale->saleDetails = $sale->saleDetails->map(function ($saleDetail) {
                            $saleDetailArray = $saleDetail->toArray();
                            $saleDetailArray['product_name'] = $saleDetailArray['product']['product_name'];
                            $saleDetailArray['hsn_code'] = $saleDetailArray['product']['hsn_sac'];
                            unset($saleDetailArray['product']);
                            return $saleDetailArray;
                        });
                        unset($sale->sale_details);
                        $sale->sale_details = $sale->saleDetails;
                        unset($sale->saleDetails);

                        $invoiceData = [
                            'invoice_name' => $sale->billType->text,
                            'invoice_no' => $sale->bill_no,
                            'date' => now()->format('d-m-Y'),
                            'due_date' => Carbon::parse($sale->due_date)->format('d-m-Y'),
                            'company' => 'PRIMENTO TECHNOLOGIES PVT LTD',
                            'address_1' => '4047, Silver Business Point, VIP Circle,',
                            'address_2' => 'Utran, Surat, Gujarat - 394101',
                            'contact_no' => '+91 70986 10111',
                            'gst_no' => '24AAOCP2783B1ZK',
                            'pan_no' => 'AAOCP2783B',
                            'lut_no' => '24AAOCP2783B1ZK',
                            'bill_to' => [
                                'name' => $sale->party->bill_name,
                                'address' => $sale->party->address,
                                'gst_no' => $sale->party->gst_no,
                            ],
                            'items' => $sale->sale_details,
                            'currency' => $sale->currency->text ?? '',
                            'total_amount' => $sale->total,
                            'total_amount_in_words' => ucwords(Helper::amountToWords($sale->total)),
                            'cgst' => $sale->cgst,
                            'sgst' => $sale->sgst,
                            'igst' => $sale->igst,
                            'invoice_total' => $sale->billType->alias == 'EX' ? $sale->sale_details->sum('dollar') : $sale->total,
                            'bank_details' => [
                                'bank' => 'AXIS BANK',
                                'branch' => 'AMROLI BRANCH',
                                'account_name' => 'PRIMENTO TECHNOLOGIES PRIVATE LIMITED',
                                'account_number' => '923020072105748',
                                'ifsc_code' => 'UTIB0003118'
                            ]
                        ];

                        $invoice_type = 'invoices.' . $sale->billType->alias ?? '';

                        $pdf = PDF::loadView($invoice_type, compact('invoiceData'));
                        $pdf->setPaper('A4', 'portrait');
                        $pdfPath = storage_path("app/public/invoices/{$sale->bill_no}.pdf");
                        $pdf->save($pdfPath);
                        $zip->addFile($pdfPath, basename($pdfPath));
                    }

                    $zip->close();

                    return response()->download(public_path('sample.zip'))->deleteFileAfterSend(true);
                }
            } else {
                // Return not found response
                return response()->json(['error' => 'Sale not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.', $e], 500);
        }
    }
}
