

/* Start Document */
jQuery(document).ready(function() {
(function ( $ ) {
  $.fn.accountMemberTree = function(member, rootOptions) {
    var tree = $('<ol class="tree" style="padding:0;">');
    var _this = this;

    rootOptions || (rootOptions = {});
    if ( !rootOptions.column )
      rootOptions.column = 'ParentId';

    var createMemberName = function(member) {
      return member.FirstName + ' ' + member.LastName + ' - ' + member.Num;
    };

    var createMemberCell = function(tree, member) {
      var cell = $('<li class="member"><label>' + createMemberName(member) + '</label></li>');
      cell.appendTo(tree);

      member.__msTree_cell = cell;
    };

    var processMembersToRows = function(parentMembers, memberRows) {
      for ( var i = 0; i < memberRows.length; i++ ) {
        processMembersToRow(parentMembers, memberRows[i]);
        parentMembers = memberRows[i];
      }

    };

    var processMembersToRow = function(parentMembers, memberRow) {
      var i, member, parentId, td, byParentId = {};

      for ( i = 0; i < memberRow.length; i++ ) {
        member = memberRow[i];
        parentId = member[rootOptions.column];

        if ( !byParentId[parentId] )
          byParentId[parentId] = [];

        byParentId[parentId].push(member);
      }

      for ( i = 0; i < parentMembers.length; i++ ) {
        member = parentMembers[i];
        if ( !byParentId[member.Id] )
          continue;

        processMembersChildren(member, byParentId[member.Id]);
      }

    };

    var processMembersChildren = function(member, children) {
      var child, cell = member.__msTree_cell;
      var ol = $('<ol></ol>');
      var id = 'msTreeSubTreeOf' + member.Id;
      cell.html(
        '<label for="' + id + '">' + createMemberName(member) + '</label>' +
        '<input type="checkbox" checked="checked" id="' + id + '"/>'
      );

      for ( var i = 0; i < children.length; i++ ) {
        createMemberCell(ol, children[i]);
      }

      ol.appendTo(cell);
    };

    /**
     * Reqeuest next rows.
     * @param  {[type]} members
     * @return {[type]}
     */
    var requestNextRows = function(members) {
      var ids = [];
      for ( var i = 0; i < members.length; i++ )
        ids.push(members[i].Id);

      $.ajax({
        url: rootOptions.getRowsUrl,
        type: 'POST',
        data: {
          ids: ids,
          column: rootOptions.column
        },

        // Code to run if the request succeeds;
        // the response is passed to the function
        success: function( result ) {
          processMembersToRows(members, result);
        },

        // Code to run if the request fails; the raw request and
        // status codes are passed to the function
        error: function( xhr, status, errorThrown ) {
            alert( "Sorry, there was a problem!" );
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
        },

        // Code to run regardless of success or failure
        complete: function( xhr, status ) {        }
      });
    };

    /* First row
    ---------------------------------------------*/
    createMemberCell(tree, member);
    requestNextRows([member]);

    tree.appendTo(this);
    return this;
  };


}( jQuery ));

/* End Document */
});