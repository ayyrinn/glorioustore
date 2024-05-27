@extends('dashboard.body.main')

@section('content')
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catalog</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="resources/css/catalogcustomer.css" />
</head>
<body>
    <div class="catalog">
        <div class="container-23">
            <div class="topbar-divider">
                <div class="topbar">
                    <div class="container-21">
                        <div class="ghost-1">
                            <img class="pxarrow-left" src="../../assets/vectors/pxarrow_left_16_x2.svg" />
                        </div>
                        <div class="ghost-2">
                            <img class="pxarrow-right" src="../../assets/vectors/pxarrow_right_10_x2.svg" />
                        </div>
                    </div>
                    <div class="ghost">
                        <img class="pxhelp-circle" src="../../assets/vectors/pxhelp_circle_3_x2.svg" />
                    </div>
                </div>
                <div class="vertical-divider"></div>
            </div>
            <div class="breadcrumbfilter">
                <div class="breadcrumb">
                    <span class="adnoc-al-dar-sharj">
                        Dashboard
                    </span>
                </div>
                <div class="seachfilter">
                  <div class="search">
                    <div class="icon-line-search">
                      <img class="ellipse-2832" src="../../assets/vectors/ellipse_28324_x2.svg" />
                    </div>
                    
                    <span class="container">
                    Cari barang...
                    </span>
                  </div>
                    <div class="categories">
                        <div class="tag-categories">
                            <span class="placeholder">
                                Semua
                            </span>
                        </div>
                        <div class="tag-categories">
                            <span class="placeholder">
                                Makanan
                            </span>
                        </div>
                        <div class="tag-categories">
                            <span class="placeholder">
                                Minuman
                            </span>
                        </div>
                        <div class="tag-categories">
                            <span class="placeholder">
                                Home and Living
                            </span>
                        </div>
                        <div class="tag-categories">
                            <span class="placeholder">
                                Personal Care
                            </span>
                        </div>
                        <div class="tag-categories">
                            <span class="placeholder">
                                Kesehatan
                            </span>
                        </div>
                        <div class="tag-categories">
                            <span class="placeholder">
                                Produk lainnya
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

@endsection
