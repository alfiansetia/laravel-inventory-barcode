@extends('layouts.template')

@section('contents')
    <div class="card">
        <div class="card-body">


            <div class="row g-2 mb-3">
                <div class="col-12 col-md-4">

                    <form action="https://demo.codenul.com/laravel/persediaan-barang/public/barang" method="GET"
                        class="position-relative">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control ps-5" value=""
                                placeholder="Cari ID atau nama barang..." autocomplete="off">
                        </div>
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-5 ms-3"
                            style="z-index: 5;"></i>
                    </form>
                </div>
                <div class="col-12 col-md-8">
                    <div class="d-grid d-md-flex gap-2 justify-content-md-end">
                        <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/tambah"
                            class="btn btn-secondary w-100 w-md-auto">
                            <i class="ti ti-plus me-1"></i>
                            Tambah Barang
                        </a>
                        <button type="button" class="btn btn-outline-primary w-100 w-md-auto" data-bs-toggle="modal"
                            data-bs-target="#modalBarcode">
                            <i class="ti ti-barcode me-1"></i>
                            Cetak Barcode
                        </button>
                        <button type="button" class="btn btn-success w-100 w-md-auto" data-bs-toggle="modal"
                            data-bs-target="#modalImport">
                            <i class="ti ti-file-import me-1"></i>
                            Import Excel
                        </button>
                        <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/export"
                            class="btn btn-danger w-100 w-md-auto">
                            <i class="ti ti-file-export me-1"></i>
                            Export Excel
                        </a>
                    </div>
                </div>
            </div>




            <div class="table-responsive border rounded mb-4">
                <div class="modal fade" id="modalHapus8912345678931" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title text-capitalize" id="exampleModalLabel">
                                    <i class="ti ti-trash me-1"></i> Hapus Data barang
                                </h1>
                            </div>
                            <div class="modal-body">

                                <p class="mb-2">
                                    Anda yakin ingin menghapus data barang <span class="fw-bold">8912345678931 -
                                        Buku</span>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default px-4" data-bs-dismiss="modal">Batal</button>

                                <form
                                    action="https://demo.codenul.com/laravel/persediaan-barang/public/barang/8912345678931"
                                    method="POST"></form>
                                <input type="hidden" name="_token" value="7PCwc5GDV7JFGOMQRxZ0ns9gb6z0OtJgdSxWvZE2"
                                    autocomplete="off"> <input type="hidden" name="_method" value="DELETE"> <button
                                    type="submit" class="btn btn-danger"> Ya, Hapus! </button>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modalHapus8912345678924" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title text-capitalize" id="exampleModalLabel">
                                    <i class="ti ti-trash me-1"></i> Hapus Data barang
                                </h1>
                            </div>
                            <div class="modal-body">

                                <p class="mb-2">
                                    Anda yakin ingin menghapus data barang <span class="fw-bold">8912345678924 -
                                        Rantai</span>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default px-4" data-bs-dismiss="modal">Batal</button>

                                <form
                                    action="https://demo.codenul.com/laravel/persediaan-barang/public/barang/8912345678924"
                                    method="POST"></form>
                                <input type="hidden" name="_token" value="7PCwc5GDV7JFGOMQRxZ0ns9gb6z0OtJgdSxWvZE2"
                                    autocomplete="off"> <input type="hidden" name="_method" value="DELETE"> <button
                                    type="submit" class="btn btn-danger"> Ya, Hapus! </button>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modalHapus8912345678917" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title text-capitalize" id="exampleModalLabel">
                                    <i class="ti ti-trash me-1"></i> Hapus Data barang
                                </h1>
                            </div>
                            <div class="modal-body">

                                <p class="mb-2">
                                    Anda yakin ingin menghapus data barang <span class="fw-bold">8912345678917 - Air
                                        Mineral</span>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default px-4"
                                    data-bs-dismiss="modal">Batal</button>

                                <form
                                    action="https://demo.codenul.com/laravel/persediaan-barang/public/barang/8912345678917"
                                    method="POST"></form>
                                <input type="hidden" name="_token" value="7PCwc5GDV7JFGOMQRxZ0ns9gb6z0OtJgdSxWvZE2"
                                    autocomplete="off"> <input type="hidden" name="_method" value="DELETE"> <button
                                    type="submit" class="btn btn-danger"> Ya, Hapus! </button>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modalHapus8912345678901" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title text-capitalize" id="exampleModalLabel">
                                    <i class="ti ti-trash me-1"></i> Hapus Data barang
                                </h1>
                            </div>
                            <div class="modal-body">

                                <p class="mb-2">
                                    Anda yakin ingin menghapus data barang <span class="fw-bold">8912345678901 - Besi
                                        Hollow 4x4</span>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default px-4"
                                    data-bs-dismiss="modal">Batal</button>

                                <form
                                    action="https://demo.codenul.com/laravel/persediaan-barang/public/barang/8912345678901"
                                    method="POST"></form>
                                <input type="hidden" name="_token" value="7PCwc5GDV7JFGOMQRxZ0ns9gb6z0OtJgdSxWvZE2"
                                    autocomplete="off"> <input type="hidden" name="_method" value="DELETE"> <button
                                    type="submit" class="btn btn-danger"> Ya, Hapus! </button>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modalHapus8912345678902" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title text-capitalize" id="exampleModalLabel">
                                    <i class="ti ti-trash me-1"></i> Hapus Data barang
                                </h1>
                            </div>
                            <div class="modal-body">

                                <p class="mb-2">
                                    Anda yakin ingin menghapus data barang <span class="fw-bold">8912345678902 - Paku
                                        5cm</span>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default px-4"
                                    data-bs-dismiss="modal">Batal</button>

                                <form
                                    action="https://demo.codenul.com/laravel/persediaan-barang/public/barang/8912345678902"
                                    method="POST"></form>
                                <input type="hidden" name="_token" value="7PCwc5GDV7JFGOMQRxZ0ns9gb6z0OtJgdSxWvZE2"
                                    autocomplete="off"> <input type="hidden" name="_method" value="DELETE"> <button
                                    type="submit" class="btn btn-danger"> Ya, Hapus! </button>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modalHapus8912345678903" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title text-capitalize" id="exampleModalLabel">
                                    <i class="ti ti-trash me-1"></i> Hapus Data barang
                                </h1>
                            </div>
                            <div class="modal-body">

                                <p class="mb-2">
                                    Anda yakin ingin menghapus data barang <span class="fw-bold">8912345678903 - Cat Tembok
                                        5kg</span>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default px-4"
                                    data-bs-dismiss="modal">Batal</button>

                                <form
                                    action="https://demo.codenul.com/laravel/persediaan-barang/public/barang/8912345678903"
                                    method="POST"></form>
                                <input type="hidden" name="_token" value="7PCwc5GDV7JFGOMQRxZ0ns9gb6z0OtJgdSxWvZE2"
                                    autocomplete="off"> <input type="hidden" name="_method" value="DELETE"> <button
                                    type="submit" class="btn btn-danger"> Ya, Hapus! </button>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modalHapus8912345678904" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title text-capitalize" id="exampleModalLabel">
                                    <i class="ti ti-trash me-1"></i> Hapus Data barang
                                </h1>
                            </div>
                            <div class="modal-body">

                                <p class="mb-2">
                                    Anda yakin ingin menghapus data barang <span class="fw-bold">8912345678904 - Kunci
                                        Inggris 12"</span>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default px-4"
                                    data-bs-dismiss="modal">Batal</button>

                                <form
                                    action="https://demo.codenul.com/laravel/persediaan-barang/public/barang/8912345678904"
                                    method="POST"></form>
                                <input type="hidden" name="_token" value="7PCwc5GDV7JFGOMQRxZ0ns9gb6z0OtJgdSxWvZE2"
                                    autocomplete="off"> <input type="hidden" name="_method" value="DELETE"> <button
                                    type="submit" class="btn btn-danger"> Ya, Hapus! </button>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modalHapus8912345678905" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title text-capitalize" id="exampleModalLabel">
                                    <i class="ti ti-trash me-1"></i> Hapus Data barang
                                </h1>
                            </div>
                            <div class="modal-body">

                                <p class="mb-2">
                                    Anda yakin ingin menghapus data barang <span class="fw-bold">8912345678905 - Bearing
                                        6201</span>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default px-4"
                                    data-bs-dismiss="modal">Batal</button>

                                <form
                                    action="https://demo.codenul.com/laravel/persediaan-barang/public/barang/8912345678905"
                                    method="POST"></form>
                                <input type="hidden" name="_token" value="7PCwc5GDV7JFGOMQRxZ0ns9gb6z0OtJgdSxWvZE2"
                                    autocomplete="off"> <input type="hidden" name="_method" value="DELETE"> <button
                                    type="submit" class="btn btn-danger"> Ya, Hapus! </button>

                            </div>
                        </div>
                    </div>
                </div>
                <table class="table align-middle text-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Jenis Barang</th>
                            <th>Lokasi</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td width="30">1</td>
                            <td width="80">8912345678931</td>
                            <td width="200">Buku</td>
                            <td width="180">Alat Tulis</td>
                            <td width="180">Gudang Utama</td>
                            <td width="100">100</td>
                            <td width="120">Pcs</td>
                            <td width="120">Rp 1</td>
                            <td width="120">Rp 1</td>
                            <td width="100">

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/detail/8912345678931"
                                    class="btn btn-warning btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Detail">
                                    <i class="ti ti-list"></i>
                                </a>

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/ubah/8912345678931"
                                    class="btn btn-secondary btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Ubah">
                                    <i class="ti ti-edit"></i>
                                </a>

                                <button type="button" class="btn btn-danger btn-sm m-1" data-bs-toggle="modal"
                                    data-bs-target="#modalHapus8912345678931" data-bs-tooltip="tooltip"
                                    data-bs-title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>



                        <tr>
                            <td width="30">2</td>
                            <td width="80">8912345678924</td>
                            <td width="200">Rantai</td>
                            <td width="180">Spare Part</td>
                            <td width="180">Gudang Sparepart</td>
                            <td width="100">106</td>
                            <td width="120">Pcs</td>
                            <td width="120">Rp 1</td>
                            <td width="120">Rp 1</td>
                            <td width="100">

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/detail/8912345678924"
                                    class="btn btn-warning btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Detail">
                                    <i class="ti ti-list"></i>
                                </a>

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/ubah/8912345678924"
                                    class="btn btn-secondary btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Ubah">
                                    <i class="ti ti-edit"></i>
                                </a>

                                <button type="button" class="btn btn-danger btn-sm m-1" data-bs-toggle="modal"
                                    data-bs-target="#modalHapus8912345678924" data-bs-tooltip="tooltip"
                                    data-bs-title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>



                        <tr>
                            <td width="30">3</td>
                            <td width="80">8912345678917</td>
                            <td width="200">Air Mineral</td>
                            <td width="180">BOTOL</td>
                            <td width="180">Gudang Material</td>
                            <td width="100">100</td>
                            <td width="120">Pack</td>
                            <td width="120">Rp 0</td>
                            <td width="120">Rp 0</td>
                            <td width="100">

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/detail/8912345678917"
                                    class="btn btn-warning btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Detail">
                                    <i class="ti ti-list"></i>
                                </a>

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/ubah/8912345678917"
                                    class="btn btn-secondary btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Ubah">
                                    <i class="ti ti-edit"></i>
                                </a>

                                <button type="button" class="btn btn-danger btn-sm m-1" data-bs-toggle="modal"
                                    data-bs-target="#modalHapus8912345678917" data-bs-tooltip="tooltip"
                                    data-bs-title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>



                        <tr>
                            <td width="30">4</td>
                            <td width="80">8912345678901</td>
                            <td width="200">Besi Hollow 4x4</td>
                            <td width="180">Bahan Baku</td>
                            <td width="180">Gudang Material</td>
                            <td width="100">160</td>
                            <td width="120">Pcs</td>
                            <td width="120">Rp 0</td>
                            <td width="120">Rp 0</td>
                            <td width="100">

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/detail/8912345678901"
                                    class="btn btn-warning btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Detail">
                                    <i class="ti ti-list"></i>
                                </a>

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/ubah/8912345678901"
                                    class="btn btn-secondary btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Ubah">
                                    <i class="ti ti-edit"></i>
                                </a>

                                <button type="button" class="btn btn-danger btn-sm m-1" data-bs-toggle="modal"
                                    data-bs-target="#modalHapus8912345678901" data-bs-tooltip="tooltip"
                                    data-bs-title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>



                        <tr>
                            <td width="30">5</td>
                            <td width="80">8912345678902</td>
                            <td width="200">Paku 5cm</td>
                            <td width="180">Bahan Pembantu</td>
                            <td width="180">Rak A1</td>
                            <td width="100">210</td>
                            <td width="120">Kg</td>
                            <td width="120">Rp 0</td>
                            <td width="120">Rp 0</td>
                            <td width="100">

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/detail/8912345678902"
                                    class="btn btn-warning btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Detail">
                                    <i class="ti ti-list"></i>
                                </a>

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/ubah/8912345678902"
                                    class="btn btn-secondary btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Ubah">
                                    <i class="ti ti-edit"></i>
                                </a>

                                <button type="button" class="btn btn-danger btn-sm m-1" data-bs-toggle="modal"
                                    data-bs-target="#modalHapus8912345678902" data-bs-tooltip="tooltip"
                                    data-bs-title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>



                        <tr>
                            <td width="30">6</td>
                            <td width="80">8912345678903</td>
                            <td width="200">Cat Tembok 5kg</td>
                            <td width="180">Bahan Baku</td>
                            <td width="180">Rak A2</td>
                            <td width="100">125</td>
                            <td width="120">Box</td>
                            <td width="120">Rp 0</td>
                            <td width="120">Rp 0</td>
                            <td width="100">

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/detail/8912345678903"
                                    class="btn btn-warning btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Detail">
                                    <i class="ti ti-list"></i>
                                </a>

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/ubah/8912345678903"
                                    class="btn btn-secondary btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Ubah">
                                    <i class="ti ti-edit"></i>
                                </a>

                                <button type="button" class="btn btn-danger btn-sm m-1" data-bs-toggle="modal"
                                    data-bs-target="#modalHapus8912345678903" data-bs-tooltip="tooltip"
                                    data-bs-title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>



                        <tr>
                            <td width="30">7</td>
                            <td width="80">8912345678904</td>
                            <td width="200">Kunci Inggris 12"</td>
                            <td width="180">Peralatan</td>
                            <td width="180">Gudang Material</td>
                            <td width="100">115</td>
                            <td width="120">Pcs</td>
                            <td width="120">Rp 0</td>
                            <td width="120">Rp 0</td>
                            <td width="100">

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/detail/8912345678904"
                                    class="btn btn-warning btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Detail">
                                    <i class="ti ti-list"></i>
                                </a>

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/ubah/8912345678904"
                                    class="btn btn-secondary btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Ubah">
                                    <i class="ti ti-edit"></i>
                                </a>

                                <button type="button" class="btn btn-danger btn-sm m-1" data-bs-toggle="modal"
                                    data-bs-target="#modalHapus8912345678904" data-bs-tooltip="tooltip"
                                    data-bs-title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>



                        <tr>
                            <td width="30">8</td>
                            <td width="80">8912345678905</td>
                            <td width="200">Bearing 6201</td>
                            <td width="180">Spare Part</td>
                            <td width="180">Gudang Sparepart</td>
                            <td width="100">140</td>
                            <td width="120">Pcs</td>
                            <td width="120">Rp 20.000</td>
                            <td width="120">Rp 25.000</td>
                            <td width="100">

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/detail/8912345678905"
                                    class="btn btn-warning btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Detail">
                                    <i class="ti ti-list"></i>
                                </a>

                                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang/ubah/8912345678905"
                                    class="btn btn-secondary btn-sm m-1" data-bs-tooltip="tooltip" data-bs-title="Ubah">
                                    <i class="ti ti-edit"></i>
                                </a>

                                <button type="button" class="btn btn-danger btn-sm m-1" data-bs-toggle="modal"
                                    data-bs-target="#modalHapus8912345678905" data-bs-tooltip="tooltip"
                                    data-bs-title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>


                    </tbody>
                </table>
            </div>

            <div class="pagination-links"></div>
        </div>
    </div>
@endsection
