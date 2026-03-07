
        
    </div>
        <!-- Normal Modal Modal -->
        <div class="modal fade" id="popupmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Media Modal -->
        <div class="modal fade" id="mediamodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li class="pull-left active">
                      <a href="#uploaded" data-toggle="tab">Gallery</a>
                    </li>
                    <li class="pull-left">
                      <a href="#upload" data-toggle="tab">Upload</a>
                    </li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="uploaded">
                    </div>
                    <div class="tab-pane" id="upload">
                      <form id="onscreen_upload">                        
                        <input type="file" id="dp_file_uploader" name="files[]" style="color:#000;">
                        <br><br>
                        <button class="btn btn-primary text-center"> Save</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary set-image">Set Image</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="mediachoosemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li class="pull-left active">
                      <a href="#uploaded" data-toggle="tab">Gallery</a>
                    </li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="uploaded">
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary set-image">Set Image</button>
              </div>
            </div>
          </div>
        </div>
        <div id="loader" style="display: none;">
          <div class="load-image">
            <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
              <path fill="#fff" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform>
              </path>
            </svg>
          </div>
        </div>
    <!-- jquery
        ============================================ -->
        <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/katex@0.11.1/dist/katex.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/katex@0.11.1/dist/katex.min.js"></script>
    <script src="{{ asset('public/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <!-- bootstrap JS
        ============================================ -->
    <script src="{{ asset('public/js/popper.js') }}"></script>
    <script src="{{ asset('public/js/kothlin/kothing-editor.min.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap.min.js') }}"></script>
    <!-- wow JS
        ============================================ -->
    <script src="{{ asset('public/js/bootstrap-toggle.min.js') }}"></script>
    
    <!-- meanmenu JS
        ============================================ -->
    <script src="{{ asset('public/js/jquery.meanmenu.js') }}"></script>
    
    <!-- sticky JS
        ============================================ -->
    <script src="{{ asset('public/js/jquery.sticky.js') }}"></script>
    <!-- scrollUp JS
        ============================================ -->
    <script src="{{ asset('public/js/jquery.scrollUp.min.js') }}"></script>
    <!-- mCustomScrollbar JS
        ============================================ -->
    <script src="{{ asset('public/js/scrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('public/js/scrollbar/mCustomScrollbar-active.js') }}"></script>
    <!-- metisMenu JS
        ============================================ -->
    <script src="{{ asset('public/js/metisMenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('public/js/metisMenu/metisMenu-active.js') }}"></script>
    <!-- float JS
        ============================================ -->
    <script src="{{ asset('public/js/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('public/js/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('public/js/flot/curvedLines.js') }}"></script>
    <script src="{{ asset('public/js/flot/flot-active.js') }}"></script>
    <!-- plugins JS
        ============================================ -->
    <script src="{{ asset('public/js/plugins.js') }}"></script>
    <!-- main JS
        ============================================ -->
    <script src="{{ asset('public/js/main.js') }}"></script>
    <!-- select picker JS
        ============================================ -->
    <script src="{{ asset('public/js/bootstrap-select.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>
   
</body>

</html>