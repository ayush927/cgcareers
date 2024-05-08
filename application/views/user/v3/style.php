<style>
  a{color:black}
  .profile-box img{
    width: 8rem;
    height: 6.5rem;
    object-fit: contain;
    margin: 10px;
  }
  .border-round{
    border:1px solid #fc9928;
  }
  .color-b{
    background-color:#fc9928;
    color:white;
  }
  .btn-my {
    color: #fff;
    background-color: #fc9928;
    border-color: #fc9928;
  }
  .btn-my:hover {
    color: #fff;
    background-color: #fc9928;
    border-color: #fc9928;
  }
  .btn-my:active {
    color: #fff;
    background-color: #fc9928;
    border-color: #fc9928;
  }
  .hover-effect:hover{
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
  }
  .translationcontainer{
    height: 200px;
    display: block;
  }
  .translationbox{
    width: 100px;
    height: 100px;
    position: absolute;
    left: 50%;
    top: 50%;
    translate: -50px -50px;
    background-color: orange;
    color: black;
    z-index: -1;
    text-align: center;
    border-radius: 50%;
    opacity: 1;
  }
  .translationbox1{
    width: 100px;
    height: 100px;
    position: absolute;
    left: 43%;
    top: 50%;
    translate: -50px -50px;
    background-color: orange;
    color: black;
    z-index: -1;
    text-align: center;
    border-radius: 50%;
    opacity: 1;
  }
  .translationbox2{
    width: 100px;
    height: 100px;
    position: absolute;
    left: 57%;
    top: 50%;
    translate: -50px -50px;
    background-color: orange;
    color: black;
    z-index: -1;
    text-align: center;
    border-radius: 50%;
    opacity: 1;
  }
  .translationbox, .translationbox1, .translationbox2{
    animation-name: transitionkey;
    animation-duration: 2s;
    animation-fill-mode:forwards;
    animation-iteration-count: infinite;
    animation-timing-function: ease-in-out;
    animation-direction: reverse;
  }
  .loading-overlay {
    background: rgba(255, 255, 255, 0.7);
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    top: 0;
    z-index: 999999;
    align-items: center;
    justify-content: center;
  }
  .loading-overlay.is-active {
      display: flex;
  }
  @keyframes transitionkey{
    0% {transform: translate(0px,0px); opacity: 1;}
    25% {transform: translate(150px,0px);opacity: 0.5;}
    50% {transform: translate(0px,0px);opacity: 1;}
    75% {transform: translate(-150px,0px);opacity: 0.5;} 
    100% {transform: translate(0px,0px);opacity: 1;}
  }
  #question{
    font-family: 'Fjalla One', Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;
    background-color: #eee;
    min-height: 190px;
    box-shadow: 0px 0px 10px #333;
    margin-bottom: 1em;
    padding: 10px 10px;
    border-radius: 5px;
  }
  ul {
    list-style-type: none;
    padding: 0;
    margin-bottom: 10px;
    font-size: smaller;
  }

  input[type="radio"] {
    margin-right: 15px;
  }
  button {
    position: relative;
    float:right;
    margin-left: 5px;
    margin-top: 15px;
  }

  #prev, #submit {
      display:none;
  }

  .msg{
    display : none;
  }
  /* #start {
      display:none;
      width: 90px;
  } */

  #warning {
    color: #800000;
    font-weight: bold;
    margin-top: 25px;
  }


  .timerDiv {

    position: absolute;

    top: 50%;

    left: 50%;

    transform: translate(-50%, -50%);

    /*font-size: 30px;*/

    color: #FC9928;

    display: flex;

    justify-content: center;

    }

    .timerDiv div {

    display: flex;

    flex-direction: column;

    align-items: center;

    }

    .timerDiv div:not(:last-child) {

    margin-right: 15px;

    }

    .timerDiv span {

    border: 2px solid;

    padding: 2px 14px;

    border-radius: 6px;

    }

    .timerDiv span:not(:last-child) {

    margin-right: 10px;

    }

  /* @media(max-width: 767px){
    #start {
      margin-left: 25px;
    }
  } */
</style>