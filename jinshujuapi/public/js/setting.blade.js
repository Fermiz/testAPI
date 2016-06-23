$(document).ready(function(){ 
  
  $('#add-prize').bootstrapValidator({
              message: '输入值无效！',
              feedbackIcons: {
                  valid: 'glyphicon glyphicon-ok',
                  invalid: 'glyphicon glyphicon-remove',
                  validating: 'glyphicon glyphicon-refresh'
              },
              fields: {
                  name: {
                      validators: {
                          notEmpty: {
                              message: '奖品名称不能为空'
                          },
                      }
                  },
                  number: {
                      validators: {
                          notEmpty: {
                              message: '奖品数量不能为空'
                          },
                          integer:{
                              message: '奖品数量必须为大于0的整数'
                          },
                          between: {
                              min: 0,
                              message: '奖品数量必须为大于0的整数'
                          },
                      }
                  },
                  chance:{
                      validators: {
                          notEmpty: {
                              message: '中奖概率不能为空'
                          },
                          numeric:{
                              message: '中奖概率必须为大于0且小于1的实数'
                          },
                          between: {
                              min: 0,
                              max: 1,
                              message: '中奖概率必须为大于0且小于1的实数'
                          },
                      }
                  }
              }
          });

});
