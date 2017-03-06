(function($){

  var roomifier;

  $.fn.roomify = function(rooms){
    roomifier = new Roomifier(this,rooms);
  }

  Roomifier = function(contentDiv,rooms){
    this._contentDiv = contentDiv;
    this.initializeOptions(rooms);
    this.currentSetup = this._options.rooms;
    this.initializeElements();
    this.renderText();
  }

  Roomifier.prototype.initializeOptions = function(rooms){
    if(rooms == null){
      rooms = {
        count: 1,
        guests: [
          {
            adults: 2
          }
        ]
      };
    }
    this._options = {
      rooms: rooms,
      text: {
        roomsLabel: "Camere",
        roomLabel: "Camera",
        adultsLabel: "Adulti",
        kidsLabel: "Copii",
        childAgesLabel: "Introdu varstele copiilor",
        roomText: "camera",
        roomsText: "camere",
        personText: "persoana",
        personsText: "persoane"
      }
    };
  }

  Roomifier.prototype.initializeInputElement = function(){
    this._contentDiv.addClass('roomifier-input-div');
    this._inputElement = $('<input>').attr({
                            type: 'text',
                            class: 'roomifier-input',
                            readonly: 'readonly'
                          });
    this._inputElement.appendTo(this._contentDiv);

    this._downArrow = $('<i>').attr({
      class: 'fa fa-chevron-down roomifier-down-arrow'
    });
    this._downArrow.appendTo(this._contentDiv);

    this._inputElement.bind('click', this, this.inputElementClickedHandler);
    this._downArrow.bind('click',this, this.inputElementClickedHandler);
    $('body').bind('click', this, this.bodyClickedHandler);
  }

  Roomifier.prototype.initializeRoomifierContent = function(){
    if(typeof this._roomifierContent !== 'undefined'){
      this._roomifierContent = $('<div>').attr({
        class: 'roomifier-content roomifier-hidden',
        style: 'top:'+this._inputElement.height()+'; left:'+this._contentDiv.position().left
      });
      this._roomifierContent.data('active',false);
      this._roomifierContent.appendTo(this._contentDiv);
    }
  }

  Roomifier.prototype.initializeRoomSelector = function(){
    this._roomsSelectorDiv = $('<div>').attr({
                              class: 'roomifier-rooms-selector'
                           });
    this._roomsSelectorLabel = $('<label>').html(this._options.text.roomsLabel);
    this._roomsSelector = $('<select>').attr({
                          id: 'noRooms'
                        });
    for(var i = 1; i <= 4; i++){
      if(i == this.currentSetup.count){
        $('<option>').attr({'value': i, 'selected': 'selected'}).text(i).appendTo(this._roomsSelector);
      } else {
        $('<option>').attr({'value': i}).text(i).appendTo(this._roomsSelector);
      }
    }
    this._roomsSelectorLabel.appendTo(this._roomsSelectorDiv);
    this._roomsSelector.appendTo(this._roomsSelectorDiv);
    this._roomsSelectorDiv.appendTo(this._roomifierContent);
    this._roomsSelector.bind('change',this,this.roomsSelectorHandler);
  }

  Roomifier.prototype.initializeRooms = function(){
    this._rooms = [];
    this._roomsContent = $('<div>').attr({
      class:"roomifier-rooms-content"
    });
    for(var i = 1; i<= 4; i++){
      this.initializeRoom(i);
    }
    this._roomsContent.appendTo(this._roomifierContent);
  }

  Roomifier.prototype.initializeRoom = function(num){
    var additionalClass = num > this.currentSetup.count ? ' roomifier-hidden-in' : '';
    this._rooms[num] = new Object();
    this._rooms[num].roomDiv = $('<div>').attr({
      class: 'roomifier-room-div clearfix'+additionalClass,
      id: 'room-'+num
    });
    this.initializeAdultsForRoom(num);
    this.initializeKidsForRoom(num);
    this._rooms[num].roomDiv.appendTo(this._roomsContent);
  }

  Roomifier.prototype.initializeKidsForRoom = function(num){
    this._rooms[num].kids = [];
    this._rooms[num].kidsSelectorDiv = $('<div>').attr({
      class: 'roomifier-kids-selector'
    });
    this._rooms[num].kidsSelectorLabel = $('<label>').html(this._options.text.kidsLabel);
    this._rooms[num].kidsSelector = $('<select>').attr({
      id: 'noKids-'+num
    });
    for(var i = 0; i <= 4; i++){
      if(this.currentSetup.guests[num - 1] != null && this.currentSetup.guests[num - 1].kids != null && this.currentSetup.guests[num - 1].kids.count == i){
        $('<option>').attr({'value': i, 'selected': 'selected'}).text(i).appendTo(this._rooms[num].kidsSelector);
      } else {
        $('<option>').attr({'value': i}).text(i).appendTo(this._rooms[num].kidsSelector);
      }
    }
    var additionalClass = '';
    if(this.currentSetup.guests[num - 1] == null || this.currentSetup.guests[num - 1].kids == null){
      additionalClass = ' roomifier-hidden-in';
    }
    this._rooms[num].agesDiv = $('<div>').attr({
      class: 'roomifier-ages-div' + additionalClass
    });
    this._rooms[num].agesLabel = $('<label>').html(this._options.text.childAgesLabel);
    this._rooms[num].agesLabel.appendTo(this._rooms[num].agesDiv);
    for(var i = 1; i <= 4; i++){
      this.initializeAgeForRoomAndKid(num,i);
    }
    this._rooms[num].kidsSelectorLabel.appendTo(this._rooms[num].kidsSelectorDiv);
    this._rooms[num].kidsSelector.appendTo(this._rooms[num].kidsSelectorDiv);
    this._rooms[num].kidsSelectorDiv.appendTo(this._rooms[num].roomDiv);
    this._rooms[num].agesDiv.appendTo(this._rooms[num].roomDiv);
    this._rooms[num].kidsSelector.bind('change',{context: this, roomNum: num},this.kidsSelectorHandler);
  }

  Roomifier.prototype.initializeAgeForRoomAndKid = function(roomNum, kidNum){
    var additionalClass = '';
    if(this.currentSetup.guests[roomNum - 1] == null || this.currentSetup.guests[roomNum - 1].kids == null ||  this.currentSetup.guests[roomNum - 1].kids[kidNum] == null){
      additionalClass = ' roomifier-hidden-in';
    }
    this._rooms[roomNum].kids[kidNum] = new Object();
    this._rooms[roomNum].kids[kidNum].ageSelectorDiv = $('<div>').attr({
      class: 'roomifier-age-selector' + additionalClass
    });
    this._rooms[roomNum].kids[kidNum].ageSelector = $('<select>').attr({
      id: 'age-'+roomNum+'-'+kidNum
    });
    for(var i = 1; i <= 17; i++){
      if(this.currentSetup.guests[roomNum - 1] != null &&
         this.currentSetup.guests[roomNum - 1].kids != null &&
         this.currentSetup.guests[roomNum - 1].kids[kidNum - 1] != null &&
         this.currentSetup.guests[num - 1].kids[kidNum - 1] == i){
           $('<option>').attr({'value': i,'selected':'selected'}).text(i).appendTo(this._rooms[roomNum].kids[kidNum].ageSelector);
      } else {
           $('<option>').attr({'value': i}).text(i).appendTo(this._rooms[roomNum].kids[kidNum].ageSelector);
      }
    }
    this._rooms[roomNum].kids[kidNum].ageSelector.appendTo(this._rooms[roomNum].kids[kidNum].ageSelectorDiv);
    this._rooms[roomNum].kids[kidNum].ageSelectorDiv.appendTo(this._rooms[roomNum].agesDiv);
    this._rooms[roomNum].kids[kidNum].ageSelector.bind('change',{context: this, roomNum: roomNum, kidNum: kidNum},this.ageSelectorHandler);
  }

  Roomifier.prototype.initializeAdultsForRoom = function(num){
    this._rooms[num].adultsSelectorDiv = $('<div>').attr({
      class: 'roomifier-adults-selector'
    });
    this._rooms[num].adultsSelectorLabel = $('<label>').html(this._options.text.adultsLabel);
    this._rooms[num].adultsSelector = $('<select>').attr({
      id: 'noAdults-'+num
    });
    for(var i = 1; i <= 6; i++){
      if(this.currentSetup.guests[num - 1] != null && this.currentSetup.guests[num - 1].adults == i){
        $('<option>').attr({'value': i, 'selected': 'selected'}).text(i).appendTo(this._rooms[num].adultsSelector);
      } else {
        $('<option>').attr({'value': i}).text(i).appendTo(this._rooms[num].adultsSelector);
      }
    }

    this._rooms[num].adultsSelectorLabel.appendTo(this._rooms[num].adultsSelectorDiv);
    this._rooms[num].adultsSelector.appendTo(this._rooms[num].adultsSelectorDiv);
    this._rooms[num].adultsSelectorDiv.appendTo(this._rooms[num].roomDiv);
    this._rooms[num].adultsSelector.bind('change',{context: this, roomNum: num},this.adultsSelectorHandler);
  }

  Roomifier.prototype.initializeElements = function(){
    this.initializeInputElement();
    this.initializeRoomifierContent();
    this.initializeRoomSelector();
    this.initializeRooms();
  }

  Roomifier.prototype.inputElementClickedHandler = function(event){
    var context = event.data;
    if(context._roomifierContent.data('active')){
      context._roomifierContent.hide();
      context._roomifierContent.data('active',false);
    } else {
      context._roomifierContent.show();
      context._roomifierContent.data('active',true);
    }
    event.stopPropagation();
  }

  /*
   *  Needs bug fix
   *  Disabled for the moment
   */
  Roomifier.prototype.bodyClickedHandler = function(event){

    var context = event.data;
    if(typeof context._roomifierContent !== 'undefined'){

      if(!context._roomifierContent.is(event.target) &&
      context._roomifierContent.has(event.target).length === 0 &&
      !context._inputElement.is(event.target) &&
      context._roomifierContent.data('active')){
        context._roomifierContent.hide();
        context._roomifierContent.data('active',false);
      }
      event.stopPropagation();
    }

  }

  Roomifier.prototype.roomsSelectorHandler = function(event){
    var context = event.data;
    var newVal = parseInt(context._roomsSelector.val());
    context.currentSetup.count = parseInt(newVal);
    for(var i = 3; i >= newVal; i--){
      context.currentSetup.guests.splice(i,1);
      context._rooms[i+1].roomDiv.addClass('roomifier-hidden-in');
      context._rooms[i+1].agesDiv.addClass('roomifier-hidden-in');
      context._rooms[i+1].adultsSelector.val(1);
      context._rooms[i+1].kidsSelector.val(0);
      for(var t = 1; t <= 4; t++){
        context._rooms[i+1].kids[t].ageSelector.val(1);
      }
    }
    for(var i = 0; i < newVal; i++){
      if(context.currentSetup.guests[i] == null ){
        context.currentSetup.guests[i] = {'adults': 1};
        context._rooms[i+1].roomDiv.removeClass('roomifier-hidden-in');
      }
    }
    context.renderText();
  }

  Roomifier.prototype.adultsSelectorHandler = function(event){
    var context = event.data.context;
    var roomNum = event.data.roomNum;
    context.currentSetup.guests[roomNum - 1].adults = parseInt(context._rooms[roomNum].adultsSelector.val());
    context.renderText();
  }

  Roomifier.prototype.kidsSelectorHandler = function(event){
    var context = event.data.context;
    var roomNum = event.data.roomNum;
    var newVal = parseInt(context._rooms[roomNum].kidsSelector.val());
    newVal != 0 ? context._rooms[roomNum].agesDiv.removeClass('roomifier-hidden-in') : context._rooms[roomNum].agesDiv.addClass('roomifier-hidden-in');
    for(var i = 3; i >= newVal; i--){
      if(context.currentSetup.guests[roomNum - 1].kids != null){
        context.currentSetup.guests[roomNum - 1].kids.splice(i,1);
      }
      context._rooms[roomNum].kids[i+1].ageSelectorDiv.addClass('roomifier-hidden-in');
      context._rooms[roomNum].kids[i+1].ageSelector.val(1);
    }
    if(context.currentSetup.guests[roomNum - 1].kids == null){
      context.currentSetup.guests[roomNum - 1].kids = [];
    }
    for(var i = 0; i < newVal; i++){
      if(context.currentSetup.guests[roomNum - 1].kids[i] == null ){
        context.currentSetup.guests[roomNum - 1].kids[i] = 1;
        context._rooms[roomNum].kids[i+1].ageSelectorDiv.removeClass('roomifier-hidden-in');
      }
    }
    if(newVal == 0){
      delete context.currentSetup.guests[roomNum - 1].kids;
    }
    context.renderText();
  }

  Roomifier.prototype.ageSelectorHandler = function(event){
    var context = event.data.context;
    var roomNum = event.data.roomNum;
    var kidNum = event.data.kidNum;
    context.currentSetup.guests[roomNum - 1].kids[kidNum - 1] = parseInt(context._rooms[roomNum].kids[kidNum].ageSelector.val());
  }

  Roomifier.prototype.renderText = function(){
    var noRooms = this.currentSetup.count;
    var noPersons = 0;
    $.each(this.currentSetup.guests, function(i,room){
      noPersons += parseInt(room.adults);
      if(room.kids != null) noPersons += room.kids.length;
    });
    var noRoomsText = noRooms + " " + (noRooms > 1 ? this._options.text.roomsText : this._options.text.roomText);
    var noPersonsText = noPersons + " " + (noPersons > 1 ? this._options.text.personsText : this._options.text.personText);
    this._inputElement.val(noRoomsText + " (" + noPersonsText + ")");
    this._contentDiv.data('value',this.currentSetup);
  }

}(jQuery));
