// TODO: refactor styles, probably move to a css file
export const pageStyle:React.CSSProperties = {
  display: 'flex',
  flexDirection: 'column',
};
export const largeListItemStyle:React.CSSProperties = {
  padding: '1em 1em',
};
export const badgeStyle:React.CSSProperties = {float:'right', marginTop:'2px'};
export const topCardStle:React.CSSProperties = {
  border: 'none',
  backgroundColor: '#f4f5f9',
  textAlign: 'right',
  boxShadow: 'none',
};
export const contentCardStyle:React.CSSProperties = {
  margin: '0px',
  padding:'0px',
  border: 'none',
  backgroundColor: 'transparent',
  textAlign: 'left',
  boxShadow: 'none',
};
export const replyNotificationCardStyle:React.CSSProperties = {
  border: 'none',
  paddingLeft: '0px',
  paddingRight: '0px',
  backgroundColor: '#f4f5f9',
  boxShadow: 'none',
  marginTop: '0px',
  flexGrow: 1,
};
export const replyMessageContentStyle:React.CSSProperties = {
  fontSize: '0.8em',
  height: '3em',
  overflow: 'hidden',
};
export const unreadStyle:React.CSSProperties = {
  fontWeight:'bold',
};
export const oneLineTruncationStyle:React.CSSProperties = {
  textOverflow:'ellipsis',
  whiteSpace: 'nowrap',
  overflow: 'hidden',
  marginTop: '0.8em',
};
