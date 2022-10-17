<h3 class="content-title"><u>Proof of Humanity - Join the General Martian Public</u></h3>
<div class="row">
  <div class="col-md-9">
    <ul id="myTab2" class="nav nav-pills">
          <li class="active" id="profile1">
            <a href="#profile-1" data-toggle="tab">Basic Profile</a>
          </li>

          <li id="profile2">
            <a href="#profile-2" data-toggle="tab">Liveness Proof</a>
          </li>

          <li id="profile3">
            <a href="#profile-3" data-toggle="tab">Publish Application</a>
          </li>

        </ul>

        <div id="myTab2Content" class="tab-content">

          <div class="tab-pane fade in active" id="profile-1">
                <div class="row">
                  <div class="col-md-4">
                    
                      <div class="fileupload-new" style="width: 250px; height: 250px;">
                        <img id="photo" src="/assets/citizen/generic_profile.jpg" class="profile-avatar-img thumbnail" alt="Profile Image" style="max-height: 100px;">
                      </div>
                      <div id="pin_message"></div>

                      <div style="display: none;" class="camera">
                      <video id="photo-video">Video stream not available.</video>
                    </div>
                    <button class="btn btn-success" id="startbutton">Take photo</button> 
                    <button class="btn btn-secondary" id="saveprofilebutton">Save photo</button> 
                    <canvas  hidden="hidden" id="canvas">
                    </canvas>
                      <script>
                          (function() {

                            var width = 320;    // We will scale the photo width to this
                            var height = 0;     // This will be computed based on the input stream
                            var streaming = false;
                            var video = null;
                            var canvas = null;
                            var photo = null;
                            var startbutton = null;

                            function startup() {
                              video = document.getElementById('photo-video');
                              canvas = document.getElementById('canvas');
                              photo = document.getElementById('photo');
                              startbutton = document.getElementById('startbutton');

                              navigator.mediaDevices.getUserMedia({video: true, audio: false})
                              .then(function(stream) {
                                video.srcObject = stream;
                                video.play();
                              })
                              .catch(function(err) {
                                console.log("An error occurred: " + err);
                              });

                              video.addEventListener('canplay', function(ev){
                                if (!streaming) {
                                  height = video.videoHeight / (video.videoWidth/width);
                                
                                  // Firefox currently has a bug where the height can't be read from
                                  // the video, so we will make assumptions if this happens.
                                
                                  if (isNaN(height)) {
                                    height = width / (4/3);
                                  }
                                
                                  video.setAttribute('width', width);
                                  video.setAttribute('height', height);
                                  canvas.setAttribute('width', width);
                                  canvas.setAttribute('height', height);
                                  streaming = true;
                                }
                              }, false);

                              startbutton.addEventListener('click', function(ev){
                                takepicture();
                                ev.preventDefault();
                              }, false);
                              

                            }

                            // Fill the photo with an indication that none has been
                            // captured.

                            function clearphoto() {
                              var context = canvas.getContext('2d');
                              context.fillStyle = "#AAA";
                              context.fillRect(0, 0, canvas.width, canvas.height);

                              var data = canvas.toDataURL('image/png');
                              photo.setAttribute('src', data);
                            }
                            
                            // Capture a photo by fetching the current contents of the video
                            // and drawing it into a canvas, then converting that to a PNG
                            // format data URL. By drawing it on an offscreen canvas and then
                            // drawing that to the screen, we can change its size and/or apply
                            // other changes before drawing it.

                            function takepicture() {
                              video.style.display = 'block';
                              var context = canvas.getContext('2d');
                              if (width && height) {
                                canvas.width = width;
                                canvas.height = height;
                                context.drawImage(video, 0, 0, width, height);
                              
                                var data = canvas.toDataURL('image/png');
                                photo.setAttribute('src', data);

                              } else {
                                clearphoto();
                              }
                            }

                            // Set up our event listener to run the startup process
                            // once loading is complete.
                            window.addEventListener('load', startup, false);
                          })();


                      </script>
                

                  </div>

                  <div class="col-md-8">

                      <form class="form form-horizontal">
                        <div class="form-group">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>First Name: <span class="required">*</span></label>
                            <input class="form-control input-form" id="firstname" name="name" type="text" value="<?=$citcache['firstname']?>" required="">
                          </div>

                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Last Name: <span class="required">*</span></label>
                            <input class="form-control input-form" id="lastname" name="name" type="text" value="<?=$citcache['lastname']?>" required="">
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Display Name: <span class="required">*</span></label>
                            <input class="form-control input-form cacheme" id="displayname" name="displayname" type="text" value="<?=$citcache['displayname']?>" required="">
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Short Bio: <span class="required">*</span></label>
                            <textarea class="form-control input-form cacheme" id="shortbio" name="shortbio" rows="6" cols="40" required="" ><?=$citcache['shortbio']?></textarea>
                          </div>
                        </div>

                      </form>
                </div>
              </div>

              


              </div> <!-- /.tab-pane -->

              <div class="tab-pane fade" id="profile-2">

                  <img id="placeholder-video" style="width: 100%;" src="/assets/citizen/video_player_placeholder.gif" >
                  <video style="display: none; width: 100%; height: 100%" id="video"  autoplay></video>
                  <video style="display:none; width: 100%; height: 100%" id="finished-video" preload="auto" controls ></video>
                  <div class="row" style="margin-left: 0px;  margin-right: 0px;">
                    <button style="float: left; margin-top: 10px;" class="btn btn-success" id="start-camera">Start Camera</button>
                    <button style="display: none; float: right; margin-top: 10px;" class="btn btn-secondary" id="savevideo">Save Video</button>
                  </div>
                  <div class="row" style="margin-left: 0px;  margin-right: 0px;">
                    <button style="float: left; margin-top: 10px;" class="btn btn-success" id="start-record">Start Recording</button>
                    <button style="float: right; margin-top: 10px;" class="btn btn-tertiary" id="stop-record">Stop Recording</button>
                  </div>

                  <script>

                  // create audio and video constraints
                  const constraintsVideo = {
                      audio: false,
                      video: true
                  }
                  const constraintsAudio = {audio: true}

                  let camera_button = document.querySelector("#start-camera");
                  let save_button = document.querySelector("#savevideo");
                  let video = document.querySelector("#video");
                  let finished_video = document.querySelector("#finished-video");
                  let placeholder_video = document.querySelector("#placeholder-video");
                  let start_button = document.querySelector("#start-record");
                  let stop_button = document.querySelector("#stop-record");
                  
                  // let download_link = document.querySelector("#download-video");

                  let camera_stream = null;
                  let audio_stream = null;
                  let media_recorder = null;
                  let blobs_recorded = [];

                  camera_button.addEventListener('click', async function() {
                      try {
                        audioStream = await navigator.mediaDevices.getUserMedia(constraintsAudio)
                        camera_stream = await navigator.mediaDevices.getUserMedia(constraintsVideo)
                        //camera_stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                        placeholder_video.style.display = "none";
                        video.style.display = "block";
                      }
                      catch(error) {
                        alert(error.message);
                        return;
                      }
                      video.srcObject = camera_stream;
                      camera_button.style.display = 'none';
                      video.style.display = 'block';
                      start_button.style.display = 'block';
                      finished_video.style.display = "none";
                      finished_video.src = null;
                  });

                  start_button.addEventListener('click', function() {
                    blobs_recorded = [];
                      start_button.classList.add('btn-tertiary')
                      start_button.classList.remove("btn-success");
                      stop_button.classList.add('btn-danger');
                      stop_button.classList.remove('btn-tertiary');
                      video = document.querySelector("#video");
                      video.style.display = "block";
                      finished_video.style.display = "none";
                      finished_video.src = "";
                      let combined = new MediaStream([...camera_stream.getVideoTracks(), ...audioStream.getAudioTracks()]);
                      media_recorder = new MediaRecorder(combined, { mimeType: 'video/webm' });

                      media_recorder.addEventListener('dataavailable', function(e) {
                        blobs_recorded.push(e.data);

                      });

                      media_recorder.addEventListener('stop', function() {
                        current_blob = null;
                        current_blob = new Blob(blobs_recorded, { type: 'video/webm' })
                        let video_local = URL.createObjectURL(current_blob);
                        video.style.display = "none";
                        finished_video.style.display = "block";
                        finished_video.src = video_local;
                      });

                      media_recorder.start(1000);

                  });

                  stop_button.addEventListener('click', function() {
                        media_recorder.stop(); 
                        stop_button.classList.add('btn-tertiary')
                        stop_button.classList.remove("btn-danger");
                        start_button.classList.add('btn-success')
                        start_button.classList.remove('btn-tertiary')
                        save_button.style.display = "block";
                        camera_button.style.display = 'block';

                  });
                  </script>



              </div> <!-- /.tab-pane -->


              <div class="tab-pane fade" id="profile-3">

              <div class="portlet">

                <div class="row">
                  <div class="col-md-3 col-sm-3">
                        <h4 class="portlet-title" style="margin-top:50px;" >
                          <u>Full Name</u> 
                        </h4>
                  </div>
                  <div class="col-md-9 col-sm-9" style="border-bottom: 1px solid #e3e3e3;">
                      <div style="margin-top: 66px;"> <div style="font-family: 'Courier Prime', monospace; float:left;margin-right: 25px;" id="s_firstname">< FIRST NAME</div> <div id="s_lastname" style="font-family: 'Courier Prime', monospace;">LAST NAME ></div></div>
                  </div>

                </div>

                
                <div class="row">
                  <div class="col-md-3 col-sm-3">
                    <h4 class="portlet-title">
                      <u>Display Name</u> 
                    </h4>
                    </div>
                  <div class="col-md-9 col-sm-9" style="border-bottom: 1px solid #e3e3e3;">
                    <div style="margin-top:16px;"><span style="font-family: 'Courier Prime', monospace; float:left;" id="s_displayname">< DISPLAY NAME ></span></div>
                  </div>

                  </div>



                  <div class="row">
                  <div class="col-md-3 col-sm-3">
                    <h4 class="portlet-title">
                      <u>Short Bio </u> 
                    </h4>
                    </div>
                  <div class="col-md-9 col-sm-9" style="border-bottom: 1px solid #e3e3e3;">
                  <div style="margin-top:16px;"><span style="font-family: 'Courier Prime', monospace; float:left" id="s_shortbio">< SHORT BIO ></span></div>
                  </div>

                  </div>


                  <div class="row">
                  <div class="col-md-3 col-sm-3">
                      <h4 class="portlet-title">
                        <u>Personal Profile Picture </u> 
                      </h4>
                      </div>
                  <div class="col-md-9 col-sm-9" style="border-bottom: 1px solid #e3e3e3;">
                  <div style="margin-top:16px;"> <span style="font-family: 'Courier Prime', monospace; float:left;" id="s_ipfs_profile_pic">< IPFS_LINK ></span></div>
                  </div>

                  </div>


                      <div class="row">
                  <div class="col-md-3 col-sm-3">
                      <h4 class="portlet-title">
                        <u>Liveness Video Message </u> 
                      </h4>
                      </div>
                  <div class="col-md-9 col-sm-9" style="border-bottom: 1px solid #e3e3e3;">
                  <div style="margin-top:16px;">  <span style="font-family: 'Courier Prime', monospace; float:left;" id="s_ipfs_liveness_vid"><?php if(!empty($citcache['liveness_link'])){ echo $citcache['liveness_link']; }else{ ?>< IPFS_LINK ><?php } ?></span></div>
                  </div>

                  </div>



                  <div class="row">
                  <div class="col-md-3 col-sm-3">
                      <h4 class="portlet-title">
                        <u>Public Address </u>
                      </h4>
                      </div>
                  <div class="col-md-9 col-sm-9" style="border-bottom: 1px solid #e3e3e3;">
                  <div style="margin-top:16px;"> <span style="font-family: 'Courier Prime', monospace; float:left;" id="s_public_address"><?=$public_address?></span></div>
                  </div>

                  </div>



                <div class="portlet-body">
                  <a id="publish" class="btn btn-primary pull-left" href="">Publish My Application</a>

                  <div id="publish_progress" class="well pull-right" style="display: none;">
                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true"></a>
                    <span id="publish_progress_message"></span>
                  </div>
                </div> 

                </div>

              </div> 
            </div> 


  </div>
  <div class="col-md-3">
            <h5 class="content-title"><u>Instructions</u></h5>

            <div class="list-group">  

              <a href="javascript:;" class="list-group-item">
                  <p id="top_help_text" class="list-group-item-text">Welcome to the <br><b>Martian Citizen Registry</b>.  <br><br>In a first step simply register your profile.  <br><br>This allows others to endorse you and elevate you to Citizen status which in turn gives you the ability to launch public proposals and vote.
                  <br><br> There are two quick parts to this application: a) Some basic information about you and b) A short video clip proving you are human<br><br> In a last step your application will be uploaded to a decentralized public file system known as IPFS. <br> Your <b>Martian Identity</b> is then automatically connected to your public Marscoin address. 
                  <br><br> Think of this as a voter database that is transparent, incorruptible, decentralized and efficient.
                </p>
              </a>
            </div> <!-- /.list-group -->

            <br>

            <div class="row">

                <div class="col-md-6 col-sm-6">
                  <div class="thumbnail">
                    <div class="thumbnail-view">
                     <a href="/assets/citizen/mars_flag_q1.png" class="thumbnail-view-hover ui-lightbox"></a>
                            <img src="/assets/citizen/mars_flag_q1.png" style="width: 100%" alt="Gallery Image">
                        </div>
                        </div>          

                </div> <!-- /.col -->


                <div class="col-md-6 col-sm-6">


                  <div class="thumbnail">
                    <div class="thumbnail-view">
                      <a href="/assets/citizen/mars_flag2.png" class="thumbnail-view-hover ui-lightbox"></a>
                            <img src="/assets/citizen/mars_flag2.png" style="width: 100%" alt="Gallery Image">
                        </div>
                        </div>        

                </div> <!-- /.col -->

            </div>
            
            <div class="row">

                <div class="col-md-6 col-sm-6">

                  <div class="thumbnail">
                    <div class="thumbnail-view">
                      <a href="/assets/citizen/mars_flag5.jpg" class="thumbnail-view-hover ui-lightbox"></a>
                            <img src="/assets/citizen/mars_flag5.jpg" style="width: 100%" alt="Gallery Image">
                        </div>
                        </div>          

                  </div> <!-- /.col -->
                  <div class="col-md-6 col-sm-6">

                      <div class="thumbnail">
                        <div class="thumbnail-view">
                          <a href="/assets/citizen/mars_flag5_q2.png" class="thumbnail-view-hover ui-lightbox"></a>
                                <img src="/assets/citizen/mars_flag5_q2.png" style="width: 100%" alt="Gallery Image">
                            </div>
                            </div>          

                      </div> <!-- /.col -->


            </div>

              </div>

          </div> <!-- /.col -->



