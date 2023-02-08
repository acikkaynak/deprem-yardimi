@extends("_layout.master")

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">

    <style>
        #datatable {
            margin: 15px 0 !important;
        }

        #source::placeholder {
            color: #cc6161 !important;
        }
    </style>
@endsection

@section('js_vendor')
    <script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
@endsection

@section('js_page')
    <script>
        function getToken() {
            $.ajax({
                url: "{{route('get-token')}}",
                type: "GET",
                success: function (data, status, xhr) {
                    const token = xhr.getResponseHeader('X-AUTH-KEY')
                    $('#token').val(token)
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        $(document).ready(function () {
            getToken()

            $(document).on('change', '#city', function () {
                const city = $(this).val();
                $.ajax({
                    url: "{{route('get_district')}}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        city: city
                    },
                    success: function (data) {
                        $('#district').empty();
                        $('#district').append('<option value="">İlçe Seçiniz</option>');
                        $.each(data.data, function (key, value) {
                            $('#district').append('<option value="' + value.district + '">' + value.district + '</option>');
                        });

                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            });
            $(document).on('change', '#district', function () {
                const district = $(this).val();
                $.ajax({
                    url: "{{route('get_street')}}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        district: district
                    },
                    success: function (data) {
                        $('#street').empty();
                        $('#street').append('<option value="">Mahalle Seçiniz</option>');
                        $.each(data.data, function (key, value) {
                            $('#street').append('<option value="' + value.street + '">' + value.street + '</option>');
                        });

                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            });

            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('dashboard.datatable')}}",
                columns: [
                    {data: 'city'},
                    {data: 'address'},
                    {data: 'address_detail'},
                    {data: 'fullname'},
                ],
                search: {
                    return: true,
                },
                language: {
                    "sDecimal": ",",
                    "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
                    "sInfo": "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
                    "sInfoEmpty": "Kayıt yok",
                    "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
                    "sInfoThousands": ".",
                    "sLengthMenu": "Sayfada _MENU_ kayıt göster",
                    "sLoadingRecords": "Yükleniyor...",
                    "sProcessing": "İşleniyor...",
                    "sSearch": "Ara:",
                    "sZeroRecords": "Eşleşen kayıt bulunamadı",
                    "oPaginate": {
                        "sFirst": "İlk",
                        "sLast": "Son",
                        "sNext": "Sonraki",
                        "sPrevious": "Önceki"
                    },
                    "oAria": {
                        "sSortAscending": ": artan sütun sıralamasını aktifleştir",
                        "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
                    },
                    "select": {
                        "rows": {
                            "_": "%d kayıt seçildi",
                            "0": "",
                            "1": "1 kayıt seçildi"
                        }
                    }
                },
            });
        });

        window.addEventListener('formSubmitted', ({detail}) => {
            if (detail.status !== 'error') {
                // re-render table when form submitted
                $('#datatable').DataTable()
                    .rows()
                    .invalidate('data')
                    .draw(false);
            }
        })
    </script>
@endsection

@section('content-side')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <livewire:address-notification-form/>
            </div>
        </div>
    </div>
@endsection

@section('content-full')
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <table id="datatable" class="table table-responsive table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th>İl/İlçe/Mahalle</th>
                        <th>Adres Bilgisi</th>
                        <th>Adres Tarifi</th>
                        <th>Ad Soyad</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
