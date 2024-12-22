<div class="form-group">
    @extends('frontend.layouts.master_no_container_header')
</div>
<div class="container-fluid">
    <div id="reader" width="600px" height="600px"></div>
</div>
<style>
    .expand-collapse-icon {
      font-size: 200px;
      width: 100%;
      height: 100%;
      position: relative;
      display: inline-block;
  }

  .expand-collapse-icon::before, .expand-collapse-icon::after {
      content: "";
      position: absolute;
      width: 1em;
      height: .16em;
      top: calc( (1em / 2 ) - .08em );
      background-color: rgb(74, 83, 71);
      transition: 0.3s ease-in-out all;
      border-radius: 0.03em;
      top: 13px;
      left: 5px;
  }

  .expand-collapse-icon::after {
      transform: rotate(90deg);
  }

  .collapsed.expand-collapse-icon::after {
      transform: rotate(180deg);
  }


  .collapsed.expand-collapse-icon::before {
      transform: rotate(90deg) scale(0);
  }
</style>
@section('scripts')
    <script>
        function onScanSuccess(decodedText, decodedResult) {
        // handle the scanned code as you like, for example:
        console.log(`Code matched = ${decodedText}`, decodedResult);
        }

        function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
        // for example:
        console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10, qrbox: {width: 250, height: 250} },
        /* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

    </script>
@endsection

