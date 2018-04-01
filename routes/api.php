<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1'], function() {

    Route::get('test', 'Api\TestController@index');
    Route::post('test', 'Api\TestController@import');

    //    Happy Call
    Route::get('happy-call-phan-trang', 'Api\HappyCallController@getPagination')->middleware('jwt');
    Route::get('happy-call-chua-thuc-hien-pagination', 'Api\HappyCallController@getHpCallNoPagination')->middleware('jwt');
    Route::get('happy-call-da-thuc-hien-pagination', 'Api\HappyCallController@getHpCallYesPagination')->middleware('jwt');

    Route::get('happy-call', 'Api\HappyCallController@getAll')->middleware('jwt');
    Route::delete('happy-call/delete', 'Api\HappyCallController@delete')->middleware('jwt');
    Route::put('happy-call/update', 'Api\HappyCallController@update')->middleware('jwt');
    Route::post('happy-call/create', 'Api\HappyCallController@create')->middleware('jwt');
    Route::post('action-happy-call', 'Api\HappyCallController@actionHpcall')->middleware('jwt');

    Route::post('get-happy-call-index', 'Api\HappyCallController@getHpcallIndex')->middleware('jwt');
    Route::get('get-happy-call-list', 'Api\HappyCallController@get_list_hpcall')->middleware('jwt');


    //    Khach Hang
    Route::get('khach-hang-phan-trang', 'Api\KhachHangController@getPagination')->middleware('jwt');
    Route::get('khach-hang', 'Api\KhachHangController@getAll')->middleware('jwt');
    Route::get('khach-hang/{id}', 'Api\KhachHangController@show')->middleware('jwt');
    Route::delete('khach-hang/delete', 'Api\KhachHangController@delete')->middleware('jwt');
    Route::put('khach-hang/update', 'Api\KhachHangController@update')->middleware('jwt');
    Route::post('khach-hang/create', 'Api\KhachHangController@create')->middleware('jwt');
    Route::get('khach-hang/filter', 'Api\KhachHangController@filter')->middleware('jwt');
    Route::get('khach-hang-get-ma-tu-sinh', 'Api\KhachHangController@getMaTuSinhKH')->middleware('jwt');
    Route::get('khach-hang-search', 'Api\KhachHangController@search');
    Route::get('show-khach-hang', 'Api\KhachHangController@show_customer');
    Route::post('createKHPublic', 'Api\KhachHangController@createPublic');



//    Nganh Hang
    Route::get('nganh-hang-phan-trang', 'Api\NganhHangController@getPagination')->middleware('jwt');
    Route::get('nganh-hang-pagination', 'Api\NganhHangController@getPagination')->middleware('jwt');

    Route::get('nganh-hang', 'Api\NganhHangController@getAll')->middleware('jwt');
    Route::delete('nganh-hang/delete', 'Api\NganhHangController@delete')->middleware('jwt');
    Route::put('nganh-hang/update', 'Api\NganhHangController@update')->middleware('jwt');
    Route::post('nganh-hang/create', 'Api\NganhHangController@create')->middleware('jwt');

    //San pham

    Route::get('san-pham-phan-trang', 'Api\SanPhamController@getPagination')->middleware('jwt');
    Route::get('san-pham', 'Api\SanPhamController@getAll')->middleware('jwt');
    Route::get('san-pham-nganh', 'Api\SanPhamController@getbyNganh')->middleware('jwt');

    Route::delete('san-pham/delete', 'Api\SanPhamController@delete')->middleware('jwt');
    Route::put('san-pham/update', 'Api\SanPhamController@update')->middleware('jwt');
    Route::post('san-pham/create', 'Api\SanPhamController@create')->middleware('jwt');

    //Model
    Route::get('model-phan-trang', 'Api\ModelController@getPagination')->middleware('jwt');
    Route::post('upload-csv', 'Api\ModelController@uploadCsv');

    Route::get('model-san-pham', 'Api\ModelController@getbyModel')->middleware('jwt');

    Route::get('model', 'Api\ModelController@getAll')->middleware('jwt');
    Route::delete('model/delete', 'Api\ModelController@delete')->middleware('jwt');
    Route::put('model/update', 'Api\ModelController@update')->middleware('jwt');
    Route::post('model/create', 'Api\ModelController@create')->middleware('jwt');


    //Serial

    Route::get('serial-phan-trang','Api\SerialController@getPagination')->middleware('jwt');
    Route::post('serial-upload-csv','Api\SerialController@uploadCsv');
    Route::post('upload-serial-excel','Api\SerialController@uploadExcel')->middleware('jwt');

    Route::get('pbh-serial','Api\SerialController@pbhSerial');
    Route::get('serial-khach-hang','Api\SerialController@serialKhachHang');

    Route::get('serial', 'Api\SerialController@getAll')->middleware('jwt');
    Route::delete('serial/delete', 'Api\SerialController@delete')->middleware('jwt');
    Route::put('serial/update', 'Api\SerialController@update')->middleware('jwt');
    Route::post('serial/create', 'Api\SerialController@create')->middleware('jwt');


    Route::get('serial/filter', 'Api\SerialController@filter')->middleware('jwt');
    Route::get('serial/{id}', 'Api\SerialController@show')->middleware('jwt');
    Route::get('serial-search', 'Api\SerialController@search');
    Route::get('get-serial-by-id', 'Api\SerialController@getSerial');
    Route::get('get-serial-by-ma', 'Api\SerialController@getSerialbyMa');
    Route::get('kich-hoat-bh', 'Api\SerialController@kichhoatBH');



    // To chuc resourses
    Route::get('to-chuc', 'Api\ToChucController@show')->middleware('jwt');
    Route::post('to-chuc/create', 'Api\ToChucController@create');


    // Cong ty resources
    Route::get('cong-ty/all', 'Api\CongTyController@all')->middleware('jwt');
    Route::post('cong-ty/create', 'Api\CongTyController@create')->middleware('jwt');
    Route::put('cong-ty/update', 'Api\CongTyController@update')->middleware('jwt');
    Route::delete('cong-ty/delete', 'Api\CongTyController@delete')->middleware('jwt');
    Route::get('cong-ty/pagination', 'Api\CongTyController@getPagination')->middleware('jwt');

    // He thong tai khoang ke toan resources
    Route::get('he-thong-tai-khoang-ke-toan/all', 'Api\HeThongTaiKhoangKeToanController@all')->middleware('jwt');
    Route::post('he-thong-tai-khoang-ke-toan/create', 'Api\HeThongTaiKhoangKeToanController@create')->middleware('jwt');;
    Route::put('he-thong-tai-khoang-ke-toan/update', 'Api\HeThongTaiKhoangKeToanController@update')->middleware('jwt');;
    Route::delete('he-thong-tai-khoang-ke-toan/delete', 'Api\HeThongTaiKhoangKeToanController@delete')->middleware('jwt');;
    Route::get('he-thong-tai-khoang-ke-toan/search', 'Api\HeThongTaiKhoangKeToanController@search');
    Route::get('he-thong-tai-khoang-ke-toan/pagination', 'Api\HeThongTaiKhoangKeToanController@getPagination')->middleware('jwt');

    // Loai chung tu resources
    Route::get('loai-chung-tu/all', 'Api\LoaiChungTuController@all')->middleware('jwt');
    Route::post('loai-chung-tu/create', 'Api\LoaiChungTuController@create')->middleware('jwt');
    Route::put('loai-chung-tu/update', 'Api\LoaiChungTuController@update')->middleware('jwt');
    Route::delete('loai-chung-tu/delete', 'Api\LoaiChungTuController@delete')->middleware('jwt');

    // So hieu chung tu resources
    Route::get('so-hieu-chung-tu/all', 'Api\SoHieuChungTuController@all')->middleware('jwt');
    Route::get('so-hieu-chung-tu/get-detail', 'Api\SoHieuChungTuController@getDetail')->middleware('jwt');
    Route::get('so-hieu-chung-tu/search', 'Api\SoHieuChungTuController@search')->middleware('jwt');
    Route::get('so-hieu-chung-tu/get-by-type', 'Api\SoHieuChungTuController@getByType')->middleware('jwt');
    Route::post('so-hieu-chung-tu/create', 'Api\SoHieuChungTuController@create')->middleware('jwt');
    Route::put('so-hieu-chung-tu/update', 'Api\SoHieuChungTuController@update');
    Route::delete('so-hieu-chung-tu/delete', 'Api\SoHieuChungTuController@delete')->middleware('jwt');
    Route::get('so-hieu-chung-tu/pagination', 'Api\SoHieuChungTuController@getPagination')->middleware('jwt');

    // Loai nguoi dung resources
    Route::get('loai-nguoi-dung/all', 'Api\LoaiNguoiDungController@all')->middleware('jwt');
    Route::post('loai-nguoi-dung/create', 'Api\LoaiNguoiDungController@create')->middleware('jwt');
    Route::put('loai-nguoi-dung/update', 'Api\LoaiNguoiDungController@update')->middleware('jwt');
    Route::delete('loai-nguoi-dung/delete', 'Api\LoaiNguoiDungController@delete')->middleware('jwt');

    // Trung tam bao hanh resources
    Route::get('trung-tam-bao-hanh/all', 'Api\TrungTamBaoHanhController@all')->middleware('jwt');
    Route::post('trung-tam-bao-hanh/create', 'Api\TrungTamBaoHanhController@create')->middleware('jwt');
    Route::put('trung-tam-bao-hanh/update', 'Api\TrungTamBaoHanhController@update')->middleware('jwt');
    Route::delete('trung-tam-bao-hanh/delete', 'Api\TrungTamBaoHanhController@delete')->middleware('jwt');
    Route::get('trung-tam-bao-hanh/get-list','Api\TrungTamBaoHanhController@getList')->middleware('jwt');
    Route::get('trung-tam-bao-hanh/phan-trang', 'Api\TrungTamBaoHanhController@pagination')->middleware('jwt');
    Route::get('trung-tam-bao-hanh/search', 'Api\TrungTamBaoHanhController@search')->middleware('jwt');

    // Tram bao hanh resources
    Route::get('tram-bao-hanh/all', 'Api\TramBaoHanhController@all')->middleware('jwt');
    Route::post('tram-bao-hanh/create', 'Api\TramBaoHanhController@create')->middleware('jwt');
    Route::put('tram-bao-hanh/update', 'Api\TramBaoHanhController@update')->middleware('jwt');
    Route::delete('tram-bao-hanh/delete', 'Api\TramBaoHanhController@delete')->middleware('jwt');
    Route::post('tram-bao-hanh/upload', 'Api\TramBaoHanhController@import')->middleware('jwt');
    Route::get('tram-bao-hanh/search', 'Api\TramBaoHanhController@search')->middleware('jwt');
    Route::get('tram-by-tinh', 'Api\TramBaoHanhController@trambyTinh')->middleware('jwt');
    Route::get('tram-bao-hanh/phan-trang', 'Api\TramBaoHanhController@pagination')->middleware('jwt');
    Route::get('tram-bao-hanh/thuoc-trung-tam', 'Api\TramBaoHanhController@getTramByTrungTam')->middleware('jwt');


    // User resources
    Route::get('user/all', 'Api\UserController@all')->middleware('jwt');
    Route::post('user/create', 'Api\UserController@create')->middleware('jwt');
    Route::put('user/update', 'Api\UserController@update')->middleware('jwt');
    Route::delete('user/delete', 'Api\UserController@delete')->middleware('jwt');
    Route::get('user/search', 'Api\UserController@search');
    Route::get('user/search-nhan-vien', 'Api\UserController@searchNhanVien')->middleware('jwt');
    Route::get('user/pagination', 'Api\UserController@getPagination')->middleware('jwt');

    // Config route
    Route::get('config/city-province', 'Api\ConfigController@cityProvinceInfo');
    Route::get('config/district', 'Api\ConfigController@getDistrict');
    Route::get('config/village', 'Api\ConfigController@getVillage');

    // Danh sach chi phi di lai resources
    Route::get('danh-sach-chi-phi-di-lai/all', 'Api\DanhSachChiPhiDiLaiController@all')->middleware('jwt');
    Route::post('danh-sach-chi-phi-di-lai/create', 'Api\DanhSachChiPhiDiLaiController@create')->middleware('jwt');
    Route::get('danh-sach-chi-phi-di-lai', 'Api\DanhSachChiPhiDiLaiController@filter')->middleware('jwt');
    Route::post('danh-sach-chi-phi-di-lai/import', 'Api\DanhSachChiPhiDiLaiController@import')->middleware('jwt');
    Route::get('cpdl-phan-trang','Api\DanhSachChiPhiDiLaiController@getPagination')->middleware('jwt');
    // Tinh trang hu hong
    Route::get('tinh-trang-hu-hong/all','Api\TinhTrangHuHongController@getAll')->middleware('jwt');
    Route::post('tinh-trang-hu-hong/create','Api\TinhTrangHuHongController@create')->middleware('jwt');
    Route::put('tinh-trang-hu-hong/update','Api\TinhTrangHuHongController@update')->middleware('jwt');
    Route::delete('tinh-trang-hu-hong/delete','Api\TinhTrangHuHongController@delete')->middleware('jwt');
    Route::post('tinh-trang-hu-hong/import','Api\TinhTrangHuHongController@import')->middleware('jwt');
    Route::get('tthh-phan-trang','Api\TinhTrangHuHongController@getPagination')->middleware('jwt');
    Route::get('tthh-search', 'Api\TinhTrangHuHongController@search');

    // Nguyen nhan hu hong
    Route::get('nguyen-nhan/all','Api\NguyenNhanController@getAll')->middleware('jwt');
    Route::post('nguyen-nhan/create','Api\NguyenNhanController@create')->middleware('jwt');
    Route::put('nguyen-nhan/update','Api\NguyenNhanController@update')->middleware('jwt');
    Route::delete('nguyen-nhan/delete','Api\NguyenNhanController@delete')->middleware('jwt');
    Route::get('nguyen-nhan/filter','Api\NguyenNhanController@filter')->middleware('jwt');
    Route::post('nguyen-nhan/import','Api\NguyenNhanController@import')->middleware('jwt');
    Route::get('nguyen-nhan-phan-trang','Api\NguyenNhanController@getPagination')->middleware('jwt');

    // Huong khac phuc
    Route::get('huong-khac-phuc/all','Api\HuongKhacPhucController@getAll')->middleware('jwt');
    Route::post('huong-khac-phuc/create','Api\HuongKhacPhucController@create')->middleware('jwt');
    Route::put('huong-khac-phuc/update','Api\HuongKhacPhucController@update')->middleware('jwt');
    Route::delete('huong-khac-phuc/delete','Api\HuongKhacPhucController@delete')->middleware('jwt');
    Route::get('huong-khac-phuc/filter','Api\HuongKhacPhucController@filter')->middleware('jwt');
    Route::post('huong-khac-phuc/import','Api\HuongKhacPhucController@import')->middleware('jwt');
    Route::get('serial-phan-trang','Api\SerialController@getPagination')->middleware('jwt');
    Route::put('danh-sach-chi-phi-di-lai/update', 'Api\DanhSachChiPhiDiLaiController@update')->middleware('jwt');
    Route::delete('danh-sach-chi-phi-di-lai/delete', 'Api\DanhSachChiPhiDiLaiController@delete')->middleware('jwt');
    Route::get('hkp-phan-trang','Api\HuongKhacPhucController@getPagination')->middleware('jwt');

    // Bang tinh cong sua chua resources
    Route::get('bang-tinh-cong-sua-chua/all', 'Api\BangTinhCongSuaChuaController@all')->middleware('jwt');
    Route::post('bang-tinh-cong-sua-chua/create', 'Api\BangTinhCongSuaChuaController@create')->middleware('jwt');
    Route::put('bang-tinh-cong-sua-chua/update', 'Api\BangTinhCongSuaChuaController@update')->middleware('jwt');
    Route::delete('bang-tinh-cong-sua-chua/delete', 'Api\BangTinhCongSuaChuaController@delete')->middleware('jwt');
    Route::get('bang-tinh-cong-sua-chua/filter', 'Api\BangTinhCongSuaChuaController@filter')->middleware('jwt');
    Route::post('bang-tinh-cong-sua-chua/upload', 'Api\BangTinhCongSuaChuaController@import')->middleware('jwt');
    Route::get('bang-tinh-cong-sua-chua/pagination', 'Api\BangTinhCongSuaChuaController@getPagination')->middleware('jwt');

    //Kho
    Route::get('kho/get_list', 'Api\KhoController@getList')->middleware('jwt');
    Route::get('kho/all', 'Api\KhoController@all')->middleware('jwt');
    Route::get('kho/bao-hanh', 'Api\KhoController@getKhoTrungTamBaoHanh')->middleware('jwt');
    Route::get('kho/get-kho-by-tram', 'Api\KhoController@getKhoByTramId')->middleware('jwt');
    Route::get('ton-kho-tot', 'Api\KhoController@tonkhototPagination')->middleware('jwt');
    Route::get('ton-kho-xau', 'Api\KhoController@tonkhoxauPagination')->middleware('jwt');
    Route::get('linh-kien-xac', 'Api\KhoController@linhkienxacPagination')->middleware('jwt');
    Route::get('kho/get-kho-tot-detail', 'Api\KhoController@getKhoTotDetail')->middleware('jwt');
    Route::get('kho/get-all-kho-tram-by-trung-tam','Api\KhoController@search');
    Route::put('kho/update','Api\KhoController@update_don_gia')->middleware('jwt');
    Route::get('kho/search-kho-xuat','Api\KhoController@searchKhoXuat')->middleware('jwt');

    //Linh kien
    Route::get('linh-kien/search', 'Api\LinhKienController@search');
    Route::get('linh-kien/search-list', 'Api\LinhKienController@searchList');
    Route::get('linh-kien/tra-linh-kien', 'Api\LinhKienController@phieutraLK');



    //No linh kien xac
    Route::get('no-linh-kien-xac', 'Api\NoLinhKienXacController@getList')->middleware('jwt');
    Route::get('phieu-sua-chua/no-linh-kien-xac-psc', 'Api\NoLinhKienXacController@getListbyPSC')->middleware('jwt');
    Route::get('no-linh-kien-xac-id', 'Api\NoLinhKienXacController@getNoLKXbyPSC')->middleware('jwt');
    Route::put('no-linh-kien/update', 'Api\NoLinhKienXacController@updateLKX')->middleware('jwt');
    Route::get('no-linh-kien-xac/ds-cho-nhap-kho', 'Api\NoLinhKienXacController@dsChoNhapKho')->middleware('jwt');
    Route::post('no-linh-kien-xac/update', 'Api\NoLinhKienXacController@update')->middleware('jwt');


    //chung tu kho
    Route::post('chung-tu/create_ct_kho_tot', 'Api\ChungTuController@createCTKhoTot')->middleware('jwt');
    Route::post('chung-tu/create_ct_kho_xac', 'Api\ChungTuController@createCTKhoXac')->middleware('jwt');
    Route::get('chung-tu/get-list-kho-tot','Api\ChungTuController@getListChungTuKhoTot')->middleware('jwt');
    Route::get('chung-tu/filter-list-kho-tot','Api\ChungTuController@filterListChungTuKhoTot')->middleware('jwt');
    Route::delete('chung-tu/delete-ct-tot','Api\ChungTuController@deleteCTKhoTot')->middleware('jwt');
    Route::get('chung-tu/get-ct-tot-detail','Api\ChungTuController@getChungTuKhoTot')->middleware('jwt');
    Route::post('chung-tu/update-ct-tot','Api\ChungTuController@updateCTKhoTot')->middleware('jwt');
    Route::get('chung-tu/get-ton-kho','Api\ChungTuController@getTonKho')->middleware('jwt');
    Route::post('chung-tu/tra-linh-kien', 'Api\ChungTuController@taoPhieuTraLinhKien')->middleware('jwt');


    //  Doi tuong phap nhan resources
    Route::get('doi-tuong-phap-nhan/all', 'Api\DoiTuongPhapNhanController@all')->middleware('jwt');
    Route::get('doi-tuong-phap-nhan/pagination', 'Api\DoiTuongPhapNhanController@getPagination')->middleware('jwt');

    Route::post('doi-tuong-phap-nhan/create', 'Api\DoiTuongPhapNhanController@create')->middleware('jwt');
    Route::put('doi-tuong-phap-nhan/update', 'Api\DoiTuongPhapNhanController@update')->middleware('jwt');
    Route::delete('doi-tuong-phap-nhan/delete', 'Api\DoiTuongPhapNhanController@delete')->middleware('jwt');
    Route::post('doi-tuong-phap-nhan/get_list', 'Api\DoiTuongPhapNhanController@getChiTiet')->middleware('jwt');
    Route::get('doi-tuong-phap-nhan/search', 'Api\DoiTuongPhapNhanController@search');

    // Don dat hang resources
    Route::get('don-dat-hang/all', 'Api\DonDatHangController@all')->middleware('jwt');
    Route::get('don-dat-hang/filter','Api\DonDatHangController@filter' )->middleware('jwt');
    Route::post('don-dat-hang/create', 'Api\DonDatHangController@create')->middleware('jwt');
    Route::delete('don-dat-hang/delete', 'Api\DonDatHangController@delete')->middleware('jwt');
    Route::get('don-dat-hang/get','Api\DonDatHangController@getDonDatHang' )->middleware('jwt');
    Route::get('don-dat-hang/search', 'Api\DonDatHangController@search');

    //  Linh kien resources
    Route::get('linh-kien/all', 'Api\LinhKienController@all')->middleware('jwt');
    Route::post('linh-kien/create', 'Api\LinhKienController@create')->middleware('jwt');
    Route::put('linh-kien/update', 'Api\LinhKienController@update')->middleware('jwt');
    Route::delete('linh-kien/delete', 'Api\LinhKienController@delete')->middleware('jwt');
    Route::post('linh-kien/check-exist','Api\LinhKienController@checkExist')->middleware('jwt');
    Route::post('linh-kien/import','Api\LinhKienController@import')->middleware('jwt');
    Route::get('linh-kien/paginate', 'Api\LinhKienController@paginate')->middleware('jwt');

    // Auth resources
    Route::post('auth/login', 'Api\AuthController@login');
    Route::get('auth/me', 'Api\AuthController@show')->middleware('jwt');
    Route::put('auth/updateAccount', 'Api\AuthController@updateAccount');

    // Phieu sua chua resources

    Route::post('phieu-sua-chua/update', 'Api\PhieuSuaChuaController@update')->middleware('jwt');
    Route::post('phieu-sua-chua/create', 'Api\PhieuSuaChuaController@create')->middleware('jwt');
    Route::get('phieu-sua-chua-phan-trang', 'Api\PhieuSuaChuaController@paginate')->middleware('jwt');
    Route::get('phieu-sua-chua/{id}', 'Api\PhieuSuaChuaController@show')->middleware('jwt');
    Route::put('phieu-sua-chua/check-in', 'Api\PhieuSuaChuaController@checkIn')->middleware('jwt');
    Route::delete('phieu-sua-chua/remove-chi-phi-di-lai', 'Api\PhieuSuaChuaController@removeChiPhiDiLai')->middleware('jwt');
    Route::post('phieu-sua-chua/update-thong-tin-dich-vu', 'Api\PhieuSuaChuaController@updateThongTinDichVu')->middleware('jwt');
    Route::put('phieu-sua-chua/update-chi-phi-di-lai', 'Api\PhieuSuaChuaController@updateChiPhiDiLai')->middleware('jwt');
    Route::post('phieu-sua-chua/updateIMG', 'Api\PhieuSuaChuaController@updateIMG')->middleware('jwt');
    Route::get('phieu-sc/check-psc-by-serial', 'Api\PhieuSuaChuaController@checkPSC');

    //Phieu de nghi cap vat tu
    Route::post('phieu-de-nghi/create', 'Api\DeNghiCapLinhKienController@create')->middleware('jwt');
    Route::post('phieu-de-nghi/update', 'Api\DeNghiCapLinhKienController@update')->middleware('jwt');
    Route::get('phieu-de-nghi/get', 'Api\DeNghiCapLinhKienController@get')->middleware('jwt');
    Route::delete('phieu-sua-chua/delete', 'Api\PhieuSuaChuaController@delete')->middleware('jwt');
    Route::get('phieu-de-nghi/change-status', 'Api\DeNghiCapLinhKienController@changeStatus')->middleware('jwt');
    Route::post('phieu-de-nghi/xac-nhan-DNLK', 'Api\DeNghiCapLinhKienController@actionDNLK')->middleware('jwt');
    Route::get('phieu-de-nghi/tao-xuat-kho', 'Api\ChungTuController@taoXuatKhoTram')->middleware('jwt');

    Route::post('create-phieu-nhap-kho', 'Api\PhieuSuaChuaController@create_phieu_nhap_kho')->middleware('jwt');
    Route::get('phieu-de-nghi/phieu-de-nghi-cap-lk', 'Api\LinhKienController@getDeNghiCapLK');
    Route::get('phieu-de-nghi-cap-lk-id', 'Api\LinhKienController@getDeNghiCapLKID');
    Route::delete('delete-phieu-de-nghi', 'Api\LinhKienController@deletePDN');
    Route::get('phieu-de-nghi-phan-trang', 'Api\DeNghiCapLinhKienController@getPagination')->middleware('jwt');

// Công việc của tôi

    Route::get('cong-viec-phan-trang', 'Api\CongViecController@getPagination')->middleware('jwt');
    Route::post('phan-cong-bao-hanh', 'Api\CongViecController@phancongBH')->middleware('jwt');


});

//Route::post('nganhhang/create_new', 'Api\');
