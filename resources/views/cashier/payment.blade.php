@extends('cashier.header')

@push('css')
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <!-- Add this inside the <head> section of your HTML document -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        @page {
            size: 8.5in 9in;
            margin-top: 4in;
        }
        /*@media print {*/
        /*    body {*/
        /*        margin: 0; !* Remove the default margin for the entire document *!*/
        /*    }*/
        /*    .card {*/
        /*        margin: 0; !* Remove margin for cards, adjust as needed *!*/
        /*    }*/

        /*    .card-body {*/
        /*        margin: 0; !* Remove margin for cards, adjust as needed *!*/
        /*    }*/
        /*    !* Add other specific styles for elements that require margin adjustments for printing *!*/
        /*}*/
    </style>
@endpush

@section('home')
    active
@endsection
@section('section')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12 col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">To'lov</h5>
                            <h6 class="card-subtitle text-danger text-danger">Malumotlarni to'gri to'ldiring. Xatolikka yo'l qo'ymang.</h6>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">O'quvchi</label>
                                    <input type="text" disabled class="form-control" value="{{ $student->name }}">
                                </div>
                                <div class="row">
                                    <div class="mb-3 col">
                                        <label class="form-label">Guruh</label>
                                        <select class="form-select flex-grow-1" id="group">
                                            <option value="all">Tanlang...</option>
                                            @foreach($student->attachs as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col">
                                        <label class="form-label">Oy</label>
                                        <select class="form-select flex-grow-1" id="monthly_payments_select">
                                            <option value="all">Tanlang...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col">
                                        <label class="form-label">To'langan summa</label>
                                        <input type="number" class="form-control" disabled id="paid" value="0">
                                    </div>
                                    <div class="mb-3 col">
                                        <label class="form-label">Summa</label>
                                        <input type="number" class="form-control" id="summa" >
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Summa</label>
                                    <input type="number" class="form-control" id="summa" value="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-check m-0">
                                        <input type="checkbox" class="form-check-input">
                                        <span class="form-check-label">To'liq to'landi</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-4 d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Faktura</h5>
                            <h6 class="card-subtitle text-danger">Chekni chiqarishni unutmang.</h6>
                        </div>
                        <div class="card-body border m-1" id="printContent">
                            <div class="row ps-5 pe-5">
                                <img src="{{ asset('logo.png') }}" class="img-fluid">
                            </div>
                            <h1 class="text-center "><b>To'landi</b></h1>
                            <div class="row h4 justify-content-between border-bottom">
                                <b class="col mb-0">Sana:</b>
                                <p class="col mb-0 text-end">{{ date('d-m-Y') }}</p>
                            </div>
                            <div class="row h4 justify-content-between">
                                <b class="col-3 mb-0">F.I.SH:</b>
                                <p class="col mb-0 text-end">Samandar Sariboyev</p>
                            </div>
                            <div class="row h4 justify-content-between">
                                <b class="col-3 mb-0">Guruh:</b>
                                <p class="col mb-0 text-end">English pre intermediate</p>
                            </div>
                            <div class="row h4 justify-content-between">
                                <b class="col-3 mb-0">Oy:</b>
                                <p class="col mb-0 text-end">Sentabr</p>
                            </div>
                            <div class="row h2 text-center border-bottom border-top">
                                <b class="col mb-0">300 000 so'm</b>
                            </div>
                            <div id="qrcode-2" class="text-center d-flex justify-content-center">

                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <button type="button" id="download-button" class="btn btn-info"><i class="align-middle" data-feather="download"></i> Yuklab olish</button>
                            <button type="button" id="printButton" onClick="printdiv('printContent');" class="btn btn-success"><i class="align-middle" data-feather="printer"></i> Chop etish</button>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </main>
@endsection


@section('js')
    <script>
        $(document).on('change', '#group', function() {
            let selectedId = $(this).val();
            let month = $('#monthly_payments_select');
            if(selectedId !== 'all'){
                $.ajax({
                    url: '{{ route('cashier.payments') }}/' + selectedId,
                    method: 'GET',
                    success: function(data) {
                        month.empty();
                        month.append($('<option></option>').attr('value', 'all').text('Tanlang...'));
                        for (var i = 0; i < data.monthly_payments.length; i++) {
                            if (data.monthly_payments[i].status == 0){
                                // Format the date using moment.js to display month names in Uzbek
                                var formattedMonth = moment(data.monthly_payments[i].month).locale('uz').format('MMMM YYYY');
                                month.append($('<option></option>').attr('value', data.monthly_payments[i].id).text(formattedMonth));
                            }
                        }
                    }
                });
            }
        });


        $(document).on('change', '#monthly_payments_select', function() {
            let selectedPaymentId = $(this).val();

            // Make another AJAX call to retrieve the payment data based on the selected payment ID
            $.ajax({
                url: '{{ route('cashier.payment') }}/' + selectedPaymentId, // Replace with your route to get payment data by ID
                method: 'GET',
                success: function(data) {
                    // Assuming the returned data contains the 'amount' property
                    let paymentAmount = data.amount;
                    let paid_amount = data.amount_paid;

                    // Update the #summa input with the amount
                    $('#summa').val(paymentAmount);
                    $('#paid').val(paid_amount);
                }
            });
        });

        function printdiv(elem) {
            var header_str = '<html><head><title>' + document.title  + '</title></head><body>';
            var footer_str = '</body></html>';
            var new_str = document.getElementById(elem).innerHTML;
            var old_str = document.body.innerHTML;
            document.body.innerHTML = header_str + new_str + footer_str;
            window.print();
            document.body.innerHTML = old_str;
            return false;
        }

        //  pdf download
        const button = document.getElementById('download-button');

        function generatePDF() {
            // Choose the element that your content will be rendered to.
            const element = document.getElementById('printContent');
            // Choose the element and save the PDF for your user.
            html2pdf().from(element).save();
        }

        button.addEventListener('click', generatePDF);


        // generate qr code
        var qrcode = new QRCode(document.getElementById("qrcode-2"), {
            text: "https://markaz.ideal-study.uz/receip/13213",
            width: 200,
            height: 200,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>
@endsection


