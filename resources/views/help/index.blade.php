@extends("_layout.master-content")

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">

    <style>
        #datatable {
            margin: 15px 0 !important;
        }

        #source::placeholder {
            color: #cc6161 !important;
        }

        #datatable_filter {
            display: flex;
            flex-direction: row-reverse;
            margin-bottom: 5px;
        }

        @media (max-width: 767px) {
            #datatable_filter {
                display: flex;
                flex-direction: column;
                margin-bottom: 5px !important;
            }

            #datatable_filter > * {
                display: block;
                width: 100% !important;
                margin-bottom: 5px !important;
            }
        }
    </style>
@endsection

@section('js_vendor')
    <script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
@endsection

@section('js_page')
    <script>
        $(document).ready(function () {
            $(document).on('keypress', '#datatable_filter > label > input[type=search]', function (e) {
                if(e.which === 13){
                    e.preventDefault()
                    $('#submit-datatable-filter').trigger('click')
                }
            })

            const urlRegex = /(https?:\/\/[^\s]+)/g;
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('help.datatable')}}",
                columns: [
                    {data: 'city'},
                    {data: 'address'},
                    {data: 'address_detail', 'fnCreatedCell': function (nTd, sData, oData, iRow, iCol) {
                            const linkedContent = oData.address_detail.replace(urlRegex, function(url) {
                                return '<a href="' + url + '" title="' + url + '" target="_blank">' +  url.substring(0, 35) + '...' + '</a>';
                            });
                            $(nTd).html(linkedContent);
                    }},
                    {data: 'maps_link', 'fnCreatedCell': function (nTd, sData, oData, iRow, iCol) {
                            if(oData.maps_link){
                                if(/https?/.test(oData.maps_link)){
                                    $(nTd).html("<a class='text-danger' href='"+oData.maps_link+"' target='_blank'>Haritaya Git</a>");
                                }else{
                                    let link = 'https://www.google.com/maps/place/' + oData.street + ',' + oData.district + '/' + oData.city_raw
                                    $(nTd).html("<a class='text-danger' href='"+link+"' target='_blank'>Haritaya Git</a>");
                                }
                            }
                        }},
                    {data: 'fullname'},
                ],
                search: {
                    return: true,
                },
                language: {
                    "sDecimal": ",",
                    "sEmptyTable": "Tabloda herhangi bir veri mevcut de??il",
                    "sInfo": "_TOTAL_ kay??ttan _START_ - _END_ aras??ndaki kay??tlar g??steriliyor",
                    "sInfoEmpty": "Kay??t yok",
                    "sInfoFiltered": "(_MAX_ kay??t i??erisinden bulunan)",
                    "sInfoThousands": ".",
                    "sLengthMenu": "Sayfada _MENU_ kay??t g??ster",
                    "sLoadingRecords": "Y??kleniyor...",
                    "sProcessing": "????leniyor...",
                    "sSearch": "Ara:",
                    "sZeroRecords": "E??le??en kay??t bulunamad??",
                    "oPaginate": {
                        "sFirst": "??lk",
                        "sLast": "Son",
                        "sNext": "Sonraki",
                        "sPrevious": "??nceki"
                    },
                    "oAria": {
                        "sSortAscending": ": artan s??tun s??ralamas??n?? aktifle??tir",
                        "sSortDescending": ": azalan s??tun s??ralamas??n?? aktifle??tir"
                    },
                    "select": {
                        "rows": {
                            "_": "%d kay??t se??ildi",
                            "0": "",
                            "1": "1 kay??t se??ildi"
                        }
                    }
                },
                initComplete : function() {
                    let input = $('.dataTables_filter input').unbind(),
                        self = this.api(),
                        $searchButton = $('<button class="btn btn-sm btn-success mr-3" style="width: 100px" id="submit-datatable-filter">')
                            .text('Ara')
                            .click(function() {
                                self.search(input.val()).draw();
                            }),
                        $clearButton = $('<button class="btn btn-sm btn-danger mr-3" style="width: 100px">')
                            .text('Temizle')
                            .click(function() {
                                input.val('');
                                $searchButton.click();
                            })
                    $('.dataTables_filter').append($searchButton, $clearButton);
                }
            });
        });
    </script>
@endsection

@section('content')
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered w-100">
                        <thead>
                        <tr>
                            <th>??l/??l??e/Mahalle</th>
                            <th>Adres Bilgisi</th>
                            <th>Adres Tarifi</th>
                            <th>Link</th>
                            <th>Ad Soyad</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
