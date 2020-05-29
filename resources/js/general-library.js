
/*
  For an RFC4122 version 4 compliant solution, this one-liner(ish) solution is the most compact I could come up with
  Taken from: https://stackoverflow.com/questions/105034/create-guid-uuid-in-javascript
*/
exports.uuidv4 = function() {
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
    var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
    return v.toString(16);
  });
}