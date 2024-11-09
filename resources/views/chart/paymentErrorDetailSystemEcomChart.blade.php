<div class="card">
    <div class="card-header border-0">
      <div class="d-flex justify-content-between">
        <h3 class="card-title">LỖI THANH TOÁN CHI TIẾT LỖI HỆ THỐNG ECOM</h3>
        <a href="javascript:void(0);">View Report</a>
      </div>
    </div>
    <div class="card-body">
      <div class="d-flex">
        {{-- <p class="d-flex flex-column">
          <span class="text-bold text-lg" id="total_oto_xemay"></span>
          <span>Tổng số Lượng Đã bán</span>
        </p> --}}
      </div>
      <!-- /.d-flex -->

      <div class="position-relative mb-4"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
        <canvas id="payment-error-detail-system-ecom" height="200" width="461" style="display: block; width: 461px; height: 200px;" class="chartjs-render-monitor"></canvas>
        <div id="legend-container-system-ecom"></div>
        {{-- <div id="legend-container">
          <ul style="display: flex; flex-direction: row; margin 0px; padding: 0px">
            <li style="align-items: center; cursor: pointer; display: flex; flex-direction: row; margin-left: 10px">
              <span style="background: rgba(255, 99, 132, 0.5) none repeat scroll 0% 0%; border-color: rgb(255, 99, 132); border-width: 3px; display: inline-block; height: 20px; margin-right: 10px; width: 20px;"></span>
              <p style="color: rgb(102, 102, 102); margin: 0px; padding: 0px;">Dataset: 1</p>
            </li>
          </ul>
        </div> --}}
      </div>

      {{-- <div class="d-flex flex-row justify-content-end">
        <span class="mr-2">
          <i class="fas fa-square text-primary"></i> Xe Oto
        </span>

        <span>
          <i class="fas fa-square text-gray"></i> Xe Máy
        </span>
      </div> --}}
    </div>
  </div>