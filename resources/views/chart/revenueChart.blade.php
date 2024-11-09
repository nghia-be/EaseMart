            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">SỐ LƯỢNG BẢO HIỂM HDI TRÊN Hi FPT 30 NGÀY GẦN NHẤT</h3>
                  <a href="javascript:void(0);">View Report</a>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <span class="text-bold text-lg" id="total_oto_xemay"></span>
                    <span>Tổng số Lượng Đã bán</span>
                  </p>
                  {{-- <p class="ml-auto d-flex flex-column text-right">
                    <span class="text-success">
                      <i class="fas fa-arrow-up"></i> 12.5%
                    </span>
                    <span class="text-muted">Since last week</span>
                  </p> --}}
                </div>
                <!-- /.d-flex -->

                <div class="position-relative mb-4"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                  <canvas id="revenue-chart" height="200" width="461" style="display: block; width: 461px; height: 200px;" class="chartjs-render-monitor"></canvas>
                </div>

                <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fas fa-square text-primary"></i> Xe Oto
                  </span>

                  <span>
                    <i class="fas fa-square text-gray"></i> Xe Máy
                  </span>
                </div>
              </div>
            </div>