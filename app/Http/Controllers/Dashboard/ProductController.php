<?php

namespace App\Http\Controllers\Dashboard;

use Exception;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Redirect;

use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Picqer\Barcode\BarcodeGeneratorHTML;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        return view('products.index', [
            'products' => Product::with(['category'])
                ->filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create', [
            'categories' => Category::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $productid = IdGenerator::generate([
            'table' => 'products',
            'field' => 'productid',
            'length' => 7,
            'prefix' => 'PR'
        ]);

        $rules = [
            'product_image' => 'image|file|max:1024',
            'productname' => 'required|string',
            'categoryid' => 'required|string',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'description' => 'nullable|string|max:250',
        ];

        $validatedData = $request->validate($rules);

        // save product code value
        $validatedData['productid'] = $productid;

        /**
         * Handle upload image with Storage.
         */
        if ($file = $request->file('product_image')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/products/';

            $file->storeAs($path, $fileName);
            $validatedData['product_image'] = $fileName;
        }

        Product::create($validatedData);

        return Redirect::route('products.index')->with('success', 'Product has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', [
            'categories' => Category::all(),
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $rules = [
            'product_image' => 'image|file|max:1024',
            'productname' => 'required|string',
            'categoryid' => 'required|string',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'description' => 'nullable|string|max:250',
        ];

        $validatedData = $request->validate($rules);

        /**
         * Handle upload image with Storage.
         */
        if ($file = $request->file('product_image')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/products/';

            /**
             * Delete photo if exists.
             */
            if($product->product_image){
                Storage::delete($path . $product->product_image);
            }

            $file->storeAs($path, $fileName);
            $validatedData['product_image'] = $fileName;
        }

        Product::where('productid', $product->productid)->update($validatedData);

        return Redirect::route('products.index')->with('success', 'Product has been updated!');
    }

    public function addstock(Request $request)
    {
        $request->validate([
            'productid' => 'required|exists:products,productid',
            'stock' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->productid);
        $product->stock += $request->stock;
        $product->save();

        return Redirect::route('products.index')->with('success', 'Stock added successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        /**
         * Delete photo if exists.
         */
        if($product->product_image){
            Storage::delete('public/products/' . $product->product_image);
        }

        Product::destroy($product->productid);

        return Redirect::route('products.index')->with('success', 'Product has been deleted!');
    }

    /**
     * Show the form for importing a new resource.
     */
    public function importView()
    {
        return view('products.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'upload_file' => 'required|file|mimes:xls,xlsx',
        ]);

        $the_file = $request->file('upload_file');

        try{
            $spreadsheet = IOFactory::load($the_file->getRealPath());
            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range( 2, $row_limit );
            $column_range = range( 'J', $column_limit );
            $startcount = 2;
            $data = array();
            foreach ( $row_range as $row ) {
                $data[] = [
                    'productname' => $sheet->getCell( 'A' . $row )->getValue(),
                    'categoryid' => $sheet->getCell( 'B' . $row )->getValue(),
                    'productid' => $sheet->getCell( 'C' . $row )->getValue(),
                    'stock' => $sheet->getCell( 'D' . $row )->getValue(),
                    'product_image' => $sheet->getCell( 'E' . $row )->getValue(),
                    'price' =>$sheet->getCell( 'F' . $row )->getValue(),
                    'description' =>$sheet->getCell( 'G' . $row )->getValue(),
                ];
                $startcount++;
            }

            Product::insert($data);

        } catch (Exception $e) {
            // $error_code = $e->errorInfo[1];
            return Redirect::route('products.index')->with('error', 'There was a problem uploading the data!');
        }
        return Redirect::route('products.index')->with('success', 'Data has been successfully imported!');
    }

    public function exportExcel($products){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($products);
            $Excel_writer = new Xls($spreadSheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Products_ExportedData.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (Exception $e) {
            return;
        }
    }

    /**
     *This function loads the customer data from the database then converts it
     * into an Array that will be exported to Excel
     */
    function exportData(){
        $products = Product::all()->sortByDesc('productid');

        $product_array [] = array(
            'Product Name',
            'Category Id',
            'Product Id',
            'Stock',
            'Product Image',
            'Price',
        );

        foreach($products as $product)
        {
            $product_array[] = array(
                'Product Name' => $product->productname,
                'Category Id' => $product->categoryid,
                'Product Id' => $product->productid,
                'Stock' => $product->stock,
                'Product Image' => $product->product_image,
                'Price' =>$product->price,
            );
        }

        $this->ExportExcel($product_array);
    }

    public function showDetail($id)
    {
        // Cari produk berdasarkan id
        $product = Product::findOrFail($id);
        
        // Tampilkan halaman detail produk
        return view('productdetailcustomer.index', ['product' => $product]);
    }
}