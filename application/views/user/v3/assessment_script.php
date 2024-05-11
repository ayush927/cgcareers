<script>
  
  reload = true , reloadPageNo = 0 , code = '<?= $code ?>', key = '<?= $partName ?>', partName = '<?= $partName ?>', base_url = '<?= base_url();?>' , show = 5 , questionCounter = 0, selections = [], qList = $('.questionsList'), prev = $('#prev') , next = $('#next') , submit = $('#submit') , currentPage = 1 , backup = {} , totalPage = 0 , currentPageData = [] , msg = $('.msg'), changeSelection = [], cardElement = '', cardBody = '' , dragDropList = [] , dragDropArray = [] , optionType = '' , optionData = '' ,  questionIndex = -1; queCompletedCount = 0; validatequestionList = 5;
  
  // if( partName == 'uce_part1_3' ){
  //   if (!location.hash) {
  //     location.hash = "#reloading";
  //     location.reload(true);
  //   } 
  //   else {
  //     location.hash = "#reloaded";
  //   }
  // }


  function getQuestion(setData = null , currentPageNo = '', newSubmittion = false ){
    // console.log( setData , currentPageNo );
    $.ajax({
      type : 'post',
      url : '<?php echo base_url()."assessment-variations/three/get-question/"; ?>'+code+'/'+partName,
      success : function(data){
        var response = JSON.parse(data);
        if( partName != 'uce_part1_3' ){
          totalPage = Math.ceil(( response.queCompleted.length+response.left.length ) / 5)
        }
        else{
          totalPage = Math.ceil(( response.queCompleted.length+response.left.length ) / 21)
        }
        if( response.status == 'success' ){
          // console.log( 'backup' , backup );
          // if( reload == true  ){
            backup = response;
            // console.log( 'backup' , backup );
          // }
          if( setData == null ){
            // console.log( totalPage , 'Hello' );
            createList( response , currentPageNo , newSubmittion );
            showButtons();
          }
        }
        else{
          window.location = base_url+'BaseController/view-code/'+code;
        }
      }
    });
  }

  getQuestion();

  function createList( questionData , currentPageChange = '' , newSubmittion = false ){
    questionIndex = -1;
    qList.html('');
    limit = questionData.limit
    option = questionData.option;
    optionType = questionData.optionType;
    optionData = questionData.optionData;
    if( questionData.queCompleted.length > 0 ){
      if( newSubmittion == true || reload == true ){
        if( ( questionData.queCompleted.length ) % parseInt( limit) > 0 ){
          currentPage = ( Math.ceil(( questionData.queCompleted.length ) / parseInt( limit) ) )
          msg.show();
          msg.html('All question has not submitted , try again');
        }
        else{
          currentPage = ( Math.ceil(( questionData.queCompleted.length ) / parseInt( limit) ) + 1 )
        }
      }
      else{
        currentPage = currentPageChange;
      }
      // if( reload == true ){
      //   reloadPageNo = currentPage;
      // }
      // console.log('currentPage' , currentPage , reloadPageNo);
      // if(typeof dragDropList[( currentPage - 1 )] === 'undefined'){
      //   dragDropList[( currentPage - 1 )] = [];
      //   // console.log( dragDropList );
      // }
      // // console.log( dragDropList );
      questionData.queCompleted.forEach( ( item , index  ) => {
        questionElement = createQuestionElement(item , index , option, optionType , 'submittedQue');
        if( typeof questionElement !== '' ){
          qList.append(questionElement)
        }
      });
    }
    // if( currentPage == reloadPageNo ){
    //   questionData.left = backup.left;
    // }
    if( questionData.left.length > 0 ){
      // console.log( questionData.left );
      if( typeof selections[(currentPage-1)] === 'undefined'){
        selections[( currentPage - 1 )] = [];
        // console.log( selections );
      }
      questionData.left.forEach( ( item , index  ) => {
        questionElement = createQuestionElement(item , index , option, optionType);
        qList.append(questionElement)
        if( currentPageData.length == 5 ){
          selections[(currentPage - 1)] = currentPageData;
        }
      });
    }
    // console.log( dragDropList );
  }

  function createQuestionElement( item , index , option = [] , optionType , submitted = null ) {
      ++questionCounter
      
      if( ( optionType == 'radios' || optionType == 'checkbox' ) && partName != 'uce_part1_3' ){
        if( questionCounter > ( currentPage*show - show ) && questionCounter <= currentPage*show   ){
          questionIndex++;
          var cardElement = $('<div>', { class: 'card card-solid hover-effect' });
          var cardBody = $('<div>', { class: 'card-body pb-0' })
          if( partName == 'uce_part2_2' ){
            var header = $(`<div class="form-group"><p><b>Q ${questionCounter}</b><br> <b>A. ${( item.av3_optA_disp != '' ? item.av3_optA_disp : item.question1 )} </b><br><b>B. ${( item.av3_optB_disp != '' ? item.av3_optB_disp : item.question2 )}</b><br><b>C. ${( item.av3_optC_disp != '' ? item.av3_optC_disp : item.question3 )}</b><br><b>D. ${( item.av3_optD_disp != '' ? item.av3_optD_disp : item.question4 )}</b><br></p></div>`);
          }
          else{
            if( optionData == 'image' ){
              var header = $(`<div class="form-group"><p><b>Q${questionCounter} : </b></p> <img class="img-fluid" src="${base_url}${(( item.asmt_variant_3_disp != '' ? item.asmt_variant_3_disp : item.question ))}" alt=""></div>`);
            }
            else{
              var header = $('<div class="form-group"><p><b>Q' + (questionCounter) + ': '+(( item.asmt_variant_3_disp != '' ? item.asmt_variant_3_disp : item.question ))+'</b></p></div>');
            }
          }
          cardBody.append(header);
          if( option.length == 0 ){
            if( partName == 'uce_part1_4' || partName == 'uce_part1_4_2' || partName == 'uce_part1_5' || partName == 'uce_part2' || partName == 'uce_part2_3' || partName == 'uce_part2_4'  || partName == 'uce_part2_6' ){
              option = [
                { 'value' : 1 , 'options' : ( item.av3_optA_disp != '' ? item.av3_optA_disp : item.optionA ) },
                { 'value' : 2 , 'options' : ( item.av3_optB_disp != '' ? item.av3_optB_disp : item.optionB ) },
                { 'value' : 3 , 'options' : ( item.av3_optC_disp != '' ? item.av3_optC_disp : item.optionC ) },
                ( item.av3_optD_disp != '' ? { 'value' : 4 , 'options' : ( item.av3_optD_disp != '' ? item.av3_optD_disp : item.optionD ) } : ( item.optionD != '' ? { 'value' : 4 , 'options' : item.optionD } : '' ))
                // { 'value' : 5 , 'option' : ( item.av3_optE_disp != '' ? item.av3_optE_disp ? item.optonE ) }
              ]
              if( typeof item.av3_optE_disp !== 'undefined'  ){
                option.push( { 'value' : 5 , 'options' : ( item.av3_optE_disp != '' ? item.av3_optE_disp : item.optionE ) } )
              }
              // console.log( option , item );
            }
            else if( partName == 'uce_part2_2' ){
              option = [
                { 'value' : 1 , 'options' : 'A-B' },
                { 'value' : 2 , 'options' : 'A-C' },
                { 'value' : 3 , 'options' : 'A-D' },
                { 'value' : 4 , 'options' : 'B-C' },
                { 'value' : 5 , 'options' : 'B-D' },
                { 'value' : 6 , 'options' : 'C-D' }
              ]
            }
            else if( partName == 'uce_part2_5' ){
              option = [
                { 'value' : 1 , 'options' : 'Same' },
                { 'value' : 2 , 'options' : 'Different' }
              ]
            }
            else if( partName == 'uce_part2_7' ){
              option = [
                { 'value' : 1 , 'options' : 'Yes' },
                { 'value' : 2 , 'options' : 'No' }
              ]
            }
          }
          var radioButtons = createRadios(option, questionCounter , item , questionCounter);
          cardBody.append(radioButtons);
          cardElement.append(cardBody);
          return cardElement;
        }
        else{
          return '';
        }
      }
      else if( partName == 'uce_part1_3' && optionType == 'checkbox' ){
        // console.log( 'ok' )
        // console.log( questionCounter , ( currentPage*limit - limit ) , questionCounter , currentPage*limit )
        if( questionCounter > ( currentPage*limit - limit ) && questionCounter <= currentPage*limit   ){
          questionIndex++;
          if( partName == 'uce_part1_3' && questionCounter == 105 ){
            validatequestionList = 1;
          }
          // if(typeof changeSelection[(currentPage-1)] !== 'undefined'){
            // currentRow = selections[(currentPage-1)][(parseInt(item.grp) - 1)][(index%show)]
            // if( submitted == null ){
                // item = (backup.left).find( ( o ) => { return parseInt(o.grp) == currentRow.grp &&  parseInt(o.qno) == currentRow.qno } );
            // }
            // else{
              // item = (backup.queCompleted).find( ( o ) => { return parseInt(o.grp) == currentRow.grp &&  parseInt(o.qno) == currentRow.qno } );
            // }
            // console.log( item , currentRow);
          // }

          
          qIndex= ( item.grp  % show ) == 0 ? 4 : ( ( item.grp  % show )  - 1);
          // console.log( index/show , index%show  , item );
          if(  index%show == 0 ){
            if( submitted != null && typeof item.ordr !== 'undefined' ){
              if(typeof selections[(currentPage-1)] !== 'undefined'){
                  console.log( selections[(currentPage-1)] );
              }
            }
            if( typeof item.ordr !== 'undefined' ){
              ++queCompletedCount;
            }
            ul = `<div class="card card-solid hover-effect">
                    <div class="card-body pb-3">
                      <button id='index${qIndex}' class='${ typeof item.ordr !== 'undefined' ? '' : 'd-none' } btn-xs btn-my float-left ml-0 mt-0' onclick="clearSelection(${qIndex})"> Reset </button>
                      <ul class="todo-list inline w-100" id='group${parseInt(item.grp)}' data-widget="todo-list">`
          }
          ul += createDragDrop( questionCounter , item , index , submitted , qIndex );
          // dragDropArray.push( { 'grp' : parseInt(item.grp) , 'qno' : parseInt(item.qno) } );
          if(  index%show == 4 ){
            ul += '</ul></div></div>';
            display = ul;
            ul = '';
            // dragDropList[( currentPage - 1 )][(item.grp-1)] = dragDropArray; 
            // dragDropArray = [];
            // console.log( dragDropList );
            return display
          }
        }
        else{
          return '';
        }
      }
      // console.log( questionCounter , ( ( currentPage - 1 )*show ) , questionCounter , currentPage*show );
  }

  function createRadios( optionsList, questionCounter , item , questionIndex ) {
      var radioList = $('<div class="form-group clearfix '+( partName == 'uce_part2_3' ? 'col-md-6 text-center' : '' )+'">');
      var radioSelected = false;
      // console.log( 'before', currentPageData , (currentPage-1));
      if( typeof selections[(currentPage-1)] !== 'undefined' ){
        if( selections[(currentPage-1)].length != 0  ){
          currentPageData = selections[( currentPage-1 )];
          if(  optionType != 'checkbox' ){
            obj = currentPageData.find( ( o ) => { return o.question == item.qno } );
            // console.log( obj );
          }
        }
      }
      for (var i = 0; i < optionsList.length; i++){
        var optionDiv = '';
        if( typeof item.ans !== 'undefined'  ){
          if( typeof obj !== 'undefined' ){
            // console.log(1)
            if( optionsList[i].options != null ){
              optionDiv=`<div class="icheck-success ${ partName == 'uce_part2_2' || partName == 'uce_part2_6' ? 'd-inline' : ( partName == 'uce_part2_3' ? 'col-md-6 text-center' : '' ) }" ${ partName == 'uce_part2_2' || partName == 'uce_part2_6' ? "style='padding: 10px;margin: 10px;'" : '' }>
              <input type="${ optionType == 'radios' ? 'radio' : 'checkbox' }" data-qid="${parseInt(item.qno)}" onclick='submitOption(this)'  id='${questionCounter+'o'+i}' ${ i == 0 ? 'required' : '' } ${obj.value == parseInt(optionsList[i].value) ? 'checked' : '' } name='que${questionCounter}' data-index='${questionIndex}' value='${optionsList[i].value}'>
                <label for='${questionCounter+'o'+i}'> 
                ${ optionData == 'image' ? `<img width="150px" height="150px"  src="${base_url}${optionsList[i].options}" alt="">` : optionsList[i].options }
                </label>
              </div>`
              if( obj.qno == parseInt(item.qno) ){
                selectedOption = (obj.value == parseInt(optionsList[i].value) ? parseInt(optionsList[i].value) : '' )
              }
            }
          }
          else{
            // console.log( 2 );
            if( optionsList[i].options != null ){
              optionDiv=`<div class="icheck-success  ${ partName == 'uce_part2_2' || partName == 'uce_part2_6' ? 'd-inline' : ( partName == 'uce_part2_3' ? 'col-md-6 text-center' : '' ) }" ${ partName == 'uce_part2_2' || partName == 'uce_part2_6' ? "style='padding: 10px;margin: 10px;'" : '' }>
                <input type="${ optionType == 'radios' ? 'radio' : 'checkbox' }" data-qid="${parseInt(item.qno)}" onclick='submitOption(this)'  id='${questionCounter+'o'+i}' data-index='${questionIndex}' ${ i == 0 ? 'required' : '' } ${ ( optionType == 'radios' ? (optionsList[i].value == item.ans ? 'checked' : '') : ( (item.ans).indexOf(optionsList[i].value)  > -1 ? 'checked' : '' ) ) } name='que${questionCounter}' value='${optionsList[i].value}'>
                <label for='${questionCounter+'o'+i}'> 
                  ${ optionData == 'image' ? `<img width="150px" height="150px"  src="${base_url}${optionsList[i].options}" alt="">` : optionsList[i].options } 
                </label>
              </div>`
              if( optionType == 'checkbox' ){
                if( (item.ans).indexOf(optionsList[i].value) > -1 ){
                  selectedOption = optionsList[i].value;
                }
              }
              else{
                selectedOption = (optionsList[i].value == item.ans ? item.ans : '' )
              }
            }
          }
          if( typeof selectedOption !== 'undefined'  ){
            if( selectedOption != '' ){
              if( optionType == 'checkbox' ){
                // console.log( option );
                if( typeof currentPageData[questionIndex] === 'undefined' ){
                  currentPageData[questionIndex] = [];
                  currentPageData[questionIndex].push({ question: parseInt(item.qno), value: parseInt(selectedOption) });
                }
                else{
                  checkedList = currentPageData[questionIndex]
                  found = checkedList.find( ( o ) => { return o.question == parseInt(item.qno), o.value == parseInt(selectedOption) } );
                  if( typeof found === 'undefined' ){
                    // console.log( found ,  selectedOption )
                    currentPageData[questionIndex].push({ question: parseInt(item.qno), value: parseInt(selectedOption) });
                  }
                }
              }
              else{
                currentPageData.push( { question: parseInt(item.qno), value: parseInt(selectedOption) } )
              }
              delete selectedOption;
            }
          }
          // console.log( 'cpage' , (currentPage - 1) );
        }
        else{
          if( typeof obj !== 'undefined'  ){
            // console.log(3)
            if( optionsList[i].options != null ){
              optionDiv=`<div class="icheck-success  ${ partName == 'uce_part2_2' || partName == 'uce_part2_6' ? 'd-inline' : ( partName == 'uce_part2_3' ? 'col-md-6 text-center' : '' ) }" ${ partName == 'uce_part2_2' || partName == 'uce_part2_6' ? "style='padding: 10px;margin: 10px;'" : '' }>
              <input type="${ optionType == 'radios' ? 'radio' : 'checkbox' }" data-qid="${parseInt(item.qno)}" onclick='submitOption(this)' name='que${questionCounter}' data-index='${questionIndex}' id='${questionCounter+'o'+i}' ${ i == 0 ? 'required' : '' } ${ obj.value == optionsList[i].value ? 'checked' : '' } value='${optionsList[i].value}'>
                <label for='${questionCounter+'o'+i}'> 
                  ${ optionData == 'image' ? `<img width="150px" height="150px"  src="${base_url}${optionsList[i].options}" alt="">` : optionsList[i].options } 
                </label>
              </div>`
            }
          }
          else{
            // console.log(4 , item)

            if( optionsList[i].options != null ){
              optionDiv=`<div class="icheck-success  ${ partName == 'uce_part2_2' || partName == 'uce_part2_6' ? 'd-inline' : ( partName == 'uce_part2_3' ? 'col-md-6 text-center' : '' ) }" ${ partName == 'uce_part2_2' || partName == 'uce_part2_6' ? "style='padding: 10px;margin: 10px;'" : '' }>
                <input type="${ optionType == 'radios' ? 'radio' : 'checkbox' }" data-qid="${parseInt(item.qno)}" onclick='submitOption(this)' name='que${questionCounter}' data-index='${questionIndex}' id='${questionCounter+'o'+i}' ${ i == 0 ? 'required' : '' } value='${optionsList[i].value}'>
                  <label for='${questionCounter+'o'+i}'> 
                    ${ optionData == 'image' ? `<img width="150px" height="150px"  src="${base_url}${optionsList[i].options}" alt="">` : optionsList[i].options } 
                  </label>
              </div>`
            }
          }
        }
        if( partName == 'uce_part2_2' ){
          if(i%3 == 2){
            optionDiv += '</br>';
          }
        }
        if( partName == 'uce_part2_6' ){
          if(i%2 == 1){
            optionDiv += '</br>';
          }
        }
        radioList.append(optionDiv);
      }
      return radioList;
  }
 
  function createDragDrop( questionCounter , item , index , submitted , qIndex ){
    if(  typeof item.ordr !== 'undefined' ){
      // console.log( 'yes' , selections);
      if( typeof selections[(currentPage-1)] !== 'undefined' ){
        if( typeof currentPageData[qIndex] === 'undefined' ){
          currentPageData[qIndex] = [];
          currentPageData[qIndex][item.ordr-1] = { grp: parseInt(item.grp), qno: parseInt(item.qno) }
        }
        else{
          currentPageData[qIndex][item.ordr-1] = { grp: parseInt(item.grp), qno: parseInt(item.qno) }
        }
        console.log
        selections[(currentPage - 1)] = currentPageData;
        // console.log(selections);
      }
      else{
        selections[(currentPage-1)] = []
        if( typeof currentPageData[qIndex] === 'undefined' ){
          currentPageData[qIndex] = [];
          currentPageData[qIndex][item.ordr-1] = { grp: parseInt(item.grp), qno: parseInt(item.qno) };
        }
        else{
          currentPageData[qIndex][item.ordr-1] = { grp: parseInt(item.grp), qno: parseInt(item.qno) };
        }
        console.log
        selections[(currentPage - 1)] = currentPageData;
        // console.log(selections);
      }
    }
    return optionDiv =
      `<div class="icheck-success" >
        <input data-qno='${ item.qno }' ${ typeof item.ordr !== 'undefined' ? 'disabled="true"' : '' } ${ typeof item.ordr !== 'undefined' ? 'checked="true"' : '' } data-grp='${ item.grp }' type="${ optionType == 'radios' ? 'radio' : 'checkbox' }" data-qid="${parseInt(item.qno)}" onclick='submitOption(this)' name='que${questionCounter}' data-index='${qIndex}' id='${'o'+item.grp+'_'+item.qno}'  value='${item.qno}'>
        <label class='w-100' for='${'o'+item.grp+'_'+item.qno}' >
          <div class='row' >
            <div class='col-md-8'>
                ${item.item}
            </div>
            <div class='col-md-4'>
              <p class='${'o'+item.grp+'_'+item.qno} text-bold text-center mb-0' class='text-right'> ${ typeof item.ordr !== 'undefined' ? 'Priority '+item.ordr : '' }</p>
            </div>
          </div>
        </label>
      </div>`
  }
  // For Drag and Drop
  // function createDragDrop(questionNumber , item , index , submitted ){
  //   return `<li>
  //             <span class="handle">
  //               <span class="text" data-qno='${ item.qno }' data-grp='${ item.grp }'>
  //                 <strong>Q${submitted != null ? item.ordr : item.qno}. </strong> ${item.item}
  //               </span>
  //             </span>
  //           </li>`
  // }  

  function showButtons(){
    if( currentPage > 1 ){ 
      if( partName != 'uce_part1_3' ){
        // prev.show(); 
      }
    }
    else{ 
      // prev.hide(); 
    }
    console.log( currentPage , totalPage );
    if( currentPage == totalPage ){ 
      next.hide(); 
      submit.show();
    }
    else{ 
      next.show();
      submit.hide();
    }
  }

  function displayPrev() {
    reload = false; 
    // changeSelection = [];
    if( typeof obj !== 'undefined' ){
      delete obj;
    }
    questionCounter = 0
    queCompletedCount = 0;
    // console.log( 'changed' , queCompletedCount );
    currentPage = currentPage-1;
    currentPageData = [];
    // getQuestion(null , currentPage);
    createList(backup , currentPage);
    showButtons()
    window.scrollTo({top: 0, behavior: 'smooth'});
    console.log(selections);
  }
  function displayNext() {
    if( typeof obj !== 'undefined' ){
      delete obj;
    }
    questionCounter = 0
              // console.log( 'changed in questionComplete' queCompletedCount );
    currentPage = currentPage+1;
    if( optionType == 'radios' || optionType == 'checkbox' ){
      currentPageData = [];
      submitData( selections, ( currentPage - 1 ) );
      // createList(backup , currentPage);
    }
    else if( optionType == 'dragdrop' ){
      submitData( dragDropList, ( currentPage - 1 ) );
    }
    // queCompletedCount = 0;
    // console.log( 'changed' , queCompletedCount );
    showButtons();
    window.scrollTo({top: 0, behavior: 'smooth'});
    console.log(selections);
  }

  function submitToNext() {
    if( typeof obj !== 'undefined' ){
      delete obj;
    }
    submitData( selections, currentPage , questionCounter  );
  }

  function submitData(selectionsArray , currentPageNo , totalQuestions = null ){
    // console.log( 'change on submit :', currentPageNo);
    last = '';
    var submitArrayPno = currentPageNo - 1;
    if(typeof selectionsArray[submitArrayPno] !== 'undefined' ){
      if( ( optionType == 'radios' || optionType == 'checkbox' ) && partName != 'uce_part1_3' ){
        // console.log( 'no' );
        // return false;
        submitArray = selectionsArray[submitArrayPno]
        if( totalQuestions == null ){
          submitArrayLength = validatequestionList; 
        }
        else{
          submitArrayLength = ( totalQuestions - submitArrayPno*show );
          last = 'lastPage';
        }
        if( submitArray.length == submitArrayLength ){
          $('.loading-overlay').removeClass('d-none');
          msg.hide();
          // console.log( changeSelection , submitArrayPno );
          if( typeof changeSelection[(submitArrayPno)] !== 'undefined' ){
            changeObject = changeSelection[(submitArrayPno)];
            changeSelection.indexOf((submitArrayPno));
            // console.log( changeSelection )
          }
          else{
            changeObject = '';
          }
        }
        else{
          // console.log('y' , currentPageNo );
          currentPage = currentPageNo
          currentPageData = submitArray
          msg.show();
          msg.html('Please Complete All Question');
          console.log( 'here 3');
          window.scrollTo({top: 0, behavior: 'smooth'});
          return;
        }
      }
      else if( optionType == 'checkbox'  && partName == 'uce_part1_3'){
        // console.log(totalQuestions);
        submitArray = selectionsArray[submitArrayPno]
        console.log( queCompletedCount , validatequestionList );
        if( queCompletedCount == validatequestionList ){
          // if( validatequestionList == 1){
          //   last = 'lastPage';
          // }
          if( typeof changeSelection[(submitArrayPno)] !== 'undefined' ){
            changeObject = changeSelection[(submitArrayPno)];
            changeSelection.splice((submitArrayPno) , 1);
            // console.log( 'yes' ,changeSelection )
            $('.loading-overlay').removeClass('d-none');
          }
          else{
            changeObject = '';
          }
        }
        else{
          currentPage = currentPageNo
          currentPageData = submitArray
          msg.show();
          msg.html('Please Complete All Question');
          console.log( 'here');
          window.scrollTo({top: 0, behavior: 'smooth'});
          return;
        }
      }

      if( typeof changeObject !== 'undefined' ){
        queCompletedCount = 0;
        msg.hide();
        $.ajax({
          type : 'POST',
          url : base_url+'assessment-variations/three/submit-answers/'+code+'/'+partName,
          data : { 'submitArray' : submitArray, 'last' : last },
          dataType: "text",
          success : function(data){
            var response = JSON.parse(data);
            // console.log(response);
            if( response.status == 'success' ){
              if( optionType == 'radios' ||  optionType == 'checkbox' ){
                if( totalQuestions != null ){
                  if( partName == 'uce_part1_5' || partName == 'uce_part2_6' || partName == 'uce_part2_7' ){
                    $('.loading-overlay').addClass('d-none');
                    window.location = base_url+'BaseController/view-code/'+code;
                  }
                  else{
                    $('.loading-overlay').addClass('d-none');
                    window.location = base_url+'assessment-variations/three/'+response.nextPart+'/'+btoa(code);
                  }
                }
                else{
                  $('.loading-overlay').addClass('d-none');
                  getQuestion(null , currentPage);
                }
              }
              // if( reloadPageNo < currentPage ){
              //   reload = true
              //   getQuestion(null , currentPage , true);
              // }
              // else{
                // getQuestion(null , currentPage);
              // }
            }
            else{
              currentPage = currentPageNo
              currentPageData = submitArray
              msg.show();
              msg.html('Not sumbitted answers, Please try again');
              window.scrollTo({top: 0, behavior: 'smooth'});
              return;
            }
          }
        });
      }
      else{
        $('.loading-overlay').addClass('d-none');
        if( partName == 'uce_part1_3' ){
          currentPage = currentPageNo
          currentPageData = submitArray
          msg.show();
          msg.html('Please Complete All Question');
          console.log( 'here 1');
          window.scrollTo({top: 0, behavior: 'smooth'});
          return;
        }
      }
    }
    else{
      currentPage = currentPageNo
      currentPageData = selectionsArray
      msg.show();
      msg.html('Please Complete All Question');
      console.log( 'here 2');
      window.scrollTo({top: 0, behavior: 'smooth'});
      return;
    }
  }
  // Creates a list of the answer choices as radio inputs 
  function submitOption(option){
    var selected = false;
    var indexNumber = currentPage-1
    queIndex = $(option).data('index');
    // console.log( queIndex );
    // return false;
    if( partName != 'uce_part1_3' ){
      questionId = $(option).data('qid');
    }
    else{
      // questionId = option.getAttribute('data-qno')
      questionId = option.getAttribute('data-grp');
    }
    optionValue = $(option).val();
    // console.log( queIndex , questionId , optionValue);
    // console.log( 'one : ' , currentPage );
    if( typeof selections[indexNumber] !== 'undefined' ){
      // console.log(1);
      if(typeof changeSelection[indexNumber] === 'undefined'){
        changeSelection[indexNumber] = { 'updated' : true }
      }
      // console.log( changeSelection );
      if( selections[indexNumber].length != 0  ){
        // console.log(1);
        currentPageData = selections[indexNumber];
        if( currentPageData.length != 0 ){
          if( optionType == 'checkbox' ){
            if( option.checked === true  ){
              // console.log(option.checked);
              if( typeof currentPageData[queIndex] === 'undefined' ){
                currentPageData[queIndex] = [];
                currentPageData[queIndex].push({ grp: parseInt(questionId), qno: parseInt(optionValue) });
                // console.log( currentPageData );
              }
              else{
                // console.log( currentPageData );
                currentPageData[queIndex].push({ grp: parseInt(questionId), qno: parseInt(optionValue) });
              }
              $('.o'+parseInt(questionId)+'_'+parseInt(optionValue)).html(' Priority '+currentPageData[queIndex].length)
              $('#o'+parseInt(questionId)+'_'+parseInt(optionValue)).prop('disabled', true);
            }
            else{
              selectedOptionList = currentPageData[queIndex]
              console.log(option.checked , selectedOptionList);
              selectedOptionList.find((item, index) => {
                if( typeof item !== 'undefined' ){
                  if( item.grp == questionId && item.qno == optionValue ) {
                    // console.log(index);
                    selectedOptionList.splice( index , 1 );
                    $('.o'+parseInt(item.grp)+'_'+parseInt(item.qno)).html('')
                    // if( selectedOptionList.length == 0 ){
                    //   currentPageData.splice( queIndex , 1 );
                    // }
                    selections[indexNumber] = currentPageData;

                    // console.log(selections);
                    return false;
                  }
                }
              });
            }
            if( typeof currentPageData[queIndex] !== 'undefined' ){
              if( currentPageData[queIndex].length > 4 ){
                $('#index'+queIndex).removeClass( 'd-none' );
                selectedOptionList = currentPageData[queIndex];
                selectedOptionList.find((item, index) => {
                  $('#o'+item.grp+'_'+item.qno).prop('disabled', true);
                });
                ++queCompletedCount;
                console.log( queCompletedCount );
              }
            }

          }
          else{
            currentPageData.find((item, index) => {
              if( item.question == questionId && item.value != optionValue ) {
                currentPageData[index] = { question: questionId, value: parseInt(optionValue) };
                selected = true;
              }
              if( item.question == questionId && item.value == optionValue ) {
                selected = true;
              }
            });
            if(!selected ){
              currentPageData.push({ question: questionId, value: parseInt(optionValue) });
              delete selected;
            }
          }
        }
        else{
          // console.log(3);
          if( optionType == 'checkbox' ){
            // console.log( option );
            if( typeof currentPageData[queIndex] === 'undefined' ){
              currentPageData[queIndex] = [];
              currentPageData[queIndex].push({ question: questionId, value: parseInt(optionValue) });
            }
            else{
              currentPageData[queIndex].push({ question: questionId, value: parseInt(optionValue) });
            }
          }
          else{
            currentPageData.push({ question: questionId, value: parseInt(optionValue) });
          }
        }
      }
      else{
        // console.log(4);
        if( optionType == 'checkbox' ){
            if( option.checked === true  ){
              // console.log(option.checked);
              if( typeof currentPageData[queIndex] === 'undefined' ){
                currentPageData[queIndex] = [];
                currentPageData[queIndex].push({ grp: parseInt(questionId), qno: parseInt(optionValue) });
                // console.log( currentPageData );
              }
              else{
                // console.log( currentPageData );
                currentPageData[queIndex].push({ grp: parseInt(questionId), qno: parseInt(optionValue) });
              }
              $('.o'+parseInt(questionId)+'_'+parseInt(optionValue)).html(' Priority '+currentPageData[queIndex].length)
              $('#o'+parseInt(questionId)+'_'+parseInt(optionValue)).prop('disabled', true);
            }
          }
          else{
            currentPageData.find((item, index) => {
              if( item.question == questionId && item.value != optionValue ) {
                currentPageData[index] = { question: questionId, value: parseInt(optionValue) };
                selected = true;
              }
              if( item.question == questionId && item.value == optionValue ) {
                selected = true;
              }
            });
            if(!selected ){
              currentPageData.push({ question: questionId, value: parseInt(optionValue) });
              delete selected;
            }
          } 
      }
    }
    else{
      // console.log(5);
      if( optionType == 'checkbox' ){
        // console.log( 'chnage' );
        // console.log( option );
        if( typeof currentPageData[queIndex] === 'undefined' ){
          currentPageData[queIndex] = [];
          currentPageData[queIndex].push({ grp: parseInt(questionId), qno: parseInt(optionValue) });
        }
        else{
          currentPageData[queIndex].push({ grp: parseInt(questionId), qno: parseInt(optionValue) });
        }

        $('.o'+parseInt(questionId)+'_'+parseInt(optionValue)).html(' Priority '+currentPageData[queIndex].length)
        $('#o'+parseInt(questionId)+'_'+parseInt(optionValue)).prop('disabled', true);
      }
      else{
        currentPageData.push({ question: questionId, value: parseInt(optionValue) });
      }
    }
    selections[indexNumber] = currentPageData;
    // console.log(selections);
  }


  function clearSelection( queIndex ){
    currentPageData = selections[( currentPage-1 )]
    // console.log( currentPageData[queIndex] )
    $('#index'+queIndex).addClass( 'd-none' )
    selectedOptionList = currentPageData[queIndex]
    selectedOptionList.find((item, index) => {
      $('#o'+item.grp+'_'+item.qno).prop('disabled', false)
      $('#o'+item.grp+'_'+item.qno).prop('checked', false)
      $('.o'+item.grp+'_'+item.qno).html('')
    });
    currentPageData[queIndex] = [];
    selections[(currentPage - 1)] = currentPageData;
    --queCompletedCount;
    console.log(queCompletedCount);
  }

  function changePlace( changePlaceItem , lastpos ){
    // console.log( changePlaceItem );
    grp = changePlaceItem.getAttribute('data-grp') 
    qno = changePlaceItem.getAttribute('data-qno')
    elementUl = $('#group'+grp);
    // console.log( elementUl );
    if( typeof dragDropList[( currentPage - 1 )] !== 'undefined' ){
      if( typeof dragDropList[( currentPage - 1 )][(grp-1)] !== 'undefined' ){
        if(typeof changeSelection[(currentPage-1)] === 'undefined'){
          changeSelection[(currentPage-1)] = { 'updated' : true }
        }
        // if( childli =  ) 
        // childrenLi =  elementUl[0].childNodes;
        // childrenLi.forEach(function(item){
          // childli = item.childNodes[1].childNodes[1]
          // console.log(childli);
          // console.log({ 'grp' : parseInt(childli.getAttribute('data-grp')) , 'qno' : parseInt(childli.getAttribute('data-qno'))});
          // dragDropArray.push({ 'grp' : parseInt(childli.getAttribute('data-grp')) , 'qno' : parseInt(childli.getAttribute('data-qno'))});
        // });
        // console.log( dragDropArray );
        dragDropList[( currentPage - 1 )][(grp-1)]  = dragDropArray;
        dragDropArray = [];
      }
    }
    // console.log(dragDropList)
  }
</script>