@extends('cashier.header')

@push('css')
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <!-- Add this inside the <head> section of your HTML document -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        @media print {
            body {
                margin: 0; /* Remove the default margin for the entire document */
            }
            .card {
                margin: 0; /* Remove margin for cards, adjust as needed */
            }

            .card-body {
                margin: 0; /* Remove margin for cards, adjust as needed */
            }
            /* Add other specific styles for elements that require margin adjustments for printing */
        }
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
                                    <label class="form-label">Email address</label>
                                    <input type="email" class="form-control" placeholder="Email">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" placeholder="Password">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Textarea</label>
                                    <textarea class="form-control" placeholder="Textarea" rows="1"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">File input</label>
                                    <input class="form-control" type="file">
                                </div>
                                <div class="mb-3">
                                    <label class="form-check m-0">
                                        <input type="checkbox" class="form-check-input">
                                        <span class="form-check-label">Check me out</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-4">
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


