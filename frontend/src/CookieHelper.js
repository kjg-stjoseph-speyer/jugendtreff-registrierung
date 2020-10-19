/**
 * Writes a cookie with the specified name, value and expiration date from now
 * @param name the name of the cookie
 * @param value the value of the cookie
 * @param expDays cookie expiration date in days from now
 */
export function writeCookie(name, value, expDays) {
  const expDate = new Date();
  expDate.setTime(expDate.getTime() + expDays*24*60*60*1000);
  const expires =  ";expires=" + expDate.toUTCString();

  document.cookie = name + "=" + value + "; expires=" + expDate.toUTCString() + "; path=/";
}

/**
 * Reads a specific value from a cookie
 * @param name the name / key of the cookie
 * @returns {string} the cookie value
 */
export function readCookie(name) {
  const key = name + "=";
  const decodedCookie = decodeURIComponent(document.cookie);
  const ca = decodedCookie.split(';');

  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) === ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) === 0) {
      return c.substring(name.length +1, c.length);
    }
  }
  return "";

}
